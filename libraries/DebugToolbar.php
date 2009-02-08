<?php defined('SYSPATH') or die('No direct script access.');

class DebugToolbar_Core {

	// system.log events
	public static $logs = array();
	
	// show the toolbar
	public static function show() 
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
		
		// render
		$template->render(true);
	}
	
	/*
	 * Hooks the system.log event to catch 
	 * all log messages and save to 
	 * self::$logs;
	 */
	public static function log($message) 
	{
		self::$logs[] = $message;
	}
	
	/*
	 * Config is only directly accessible from inside
	 * the Kohana core class.  So, unfortunately, I have
	 * to go through and load every config file manually. 
	 * This is pretty inneficient but I can't think of a way
	 * around it.  Hopefully Kohana will add events for
	 * config read/write in the future.
	 */
	private static function load_config() 
	{	
		// paths to config dirs
		$paths = array(
			APPPATH.'config/',
			SYSPATH.'config/'
		);
		
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
					
					// let Kohana find full path to file
					if ($files = Kohana::find_file('config', $filename))
					{
						foreach ($files as $file)
						{
							require $file;
							
							// if file is a valid config file,
							// load/merge config array
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
			return substr($filename, 0, $pos);
		else 
			return $filename;
	}

}