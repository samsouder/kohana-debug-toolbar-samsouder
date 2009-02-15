<?php defined('SYSPATH') or die('No direct script access.');

class DebugToolbar_Core {

	// system.log events
	public static $logs = array();
	
	// show the toolbar
	public static function render($print = false) 
	{
		$template = new View('toolbar');
		
		$template->set('queries', self::queries());
		$template->set('benchmarks', self::benchmarks());
		$template->set('logs', self::logs());
		$template->set('configs', self::configs());
		
		$template->set('styles', file_get_contents(Kohana::find_file('views', 'toolbar', false, 'css')));
		$template->set('scripts', file_get_contents(Kohana::find_file('views', 'toolbar', true, 'js')));
		
		if (Event::$data and Kohana::config('debug_toolbar.auto_render'))
		{			
			/*
			 * Inject toolbar html before </body> tag.  If there is
			 * no closing body tag, I dont know what to do :P
			 */
			Event::$data = preg_replace('/<\/body>/', $template->render(false) . '</body>', Event::$data);
		}
		else
		{
			$template->render($print);
		}
	}
	
	/*
	 * Hooks the system.log event to catch 
	 * all log messages and save to 
	 * self::$logs;
	 */
	public static function log() 
	{
		self::$logs[] = Event::$data;
	}
	
	public static function logs()
	{
		return self::$logs;
	}
	
	public static function queries()
	{
		return Database::$benchmarks;
	}
	
	public static function benchmarks()
	{
		$benchmarks = array();
		foreach (Benchmark::get(true) as $name => $benchmark)
		{
			$benchmarks[$name] = array(
				'name'   => ucwords(str_replace(array('_', '-'), ' ', str_replace(SYSTEM_BENCHMARK.'_', '', $name))),
				'time'   => $benchmark['time'],
				'memory' => $benchmark['memory']
			);
		}
		$benchmarks = array_slice($benchmarks, 1) + array_slice($benchmarks, 0, 1);
		return $benchmarks;
	}
	
	/*
	 * Config is only directly accessible from inside
	 * the Kohana core class.  So, unfortunately, I have
	 * to go through and load every config file manually. 
	 * This is pretty inneficient but I can't think of a way
	 * around it.
	 */
	private static function configs() 
	{	
		if (Kohana::config('debug_toolbar.skip_configs') === true)
		{
			return array();
		}
		
		// paths to application and system config
		$paths = array(
			APPPATH.'config/', 
			SYSPATH.'config/'
		);
		
		// paths to module config
		foreach ((array)Kohana::config('core.modules') as $modpath)
		{
			if (is_dir("$modpath/config/"))
			{
				$paths[] = "$modpath/config/";
			}
		}
		
		$configuration = array();
		
		// load and merge configs in each path
		foreach ($paths as $path) 
		{
			if ($handle = opendir($path)) 
			{
				// read all files in config dir
				while (($file = readdir($handle)) !== false) 
				{
					// remove file extension from file name
					$filename = self::_strip_ext($file);
					
					// filter skip configs
					if (in_array($filename, (array)Kohana::config('debug_toolbar.skip_configs')))
					{
						continue;
					}
					
					// let Kohana find full path to file
					if ($files = Kohana::find_file('config', $filename))
					{
						foreach ($files as $file)
						{
							require $file;
							if (isset($config) AND is_array($config))
							{
								$configuration[$filename] = isset($configuration[$filename]) ? array_merge($configuration[$filename], $config) : $config;
							}
							$config = array();
						}
					}
				}
			}
		}
		return $configuration;
	}
	
	// return a filename without extension
	private static function _strip_ext($filename)
	{
		if (($pos = strrpos($filename, '.')) !== false)
		{
			return substr($filename, 0, $pos);
		}
		else
		{
			return $filename;
		}
	}

}