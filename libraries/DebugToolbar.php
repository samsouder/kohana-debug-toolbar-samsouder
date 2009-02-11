<?php defined('SYSPATH') or die('No direct script access.');

class DebugToolbar_Core {

	// system.log events
	public static $logs = array();
	
	// show the toolbar
	public static function render($print = false) 
	{
		// load dev toolbar view
		$template = new View('toolbar');
		
		// set view data
		$template->set('queries', Database::$benchmarks);
		$template->set('benchmarks', Benchmark::get(true));
		$template->set('logs', self::$logs);
		$template->set('config', self::load_config());
		$template->set('styles', file_get_contents(Kohana::find_file('views', 'toolbar', false, 'css')));
		$template->set('scripts', file_get_contents(Kohana::find_file('views', 'toolbar', true, 'js')));
		
		if (Event::$data and Kohana::config('debug_toolbar.auto_render'))
		{
			// inject toolbar into end of HTML
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
	
	/*
	 * Config is only directly accessible from inside
	 * the Kohana core class.  So, unfortunately, I have
	 * to go through and load every config file manually. 
	 * This is pretty inneficient but I can't think of a way
	 * around it.
	 */
	private static function load_config() 
	{	
		if (Kohana::config('debug_toolbar.skip_configs') === true)
		{
			return array();
		}
		
		// paths to application and system config
		$paths = array(APPPATH.'config/', SYSPATH.'config/');
		
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
				while (false !== ($file = readdir($handle))) 
				{
					// remove file extension from file name
					$filename = self::strip_ext($file);
					
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
								if (empty($configuration[$filename]))
								{
									$configuration[$filename] = $config;
								} 
								else 
								{
									$configuration[$filename] = array_merge($configuration[$filename], $config);
								}
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
	private static function strip_ext($filename)
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