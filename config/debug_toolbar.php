<?php defined('SYSPATH') or die('No direct script access.');

// If true, the debug toolbar will be automagically displayed
$config['auto_render'] = TRUE;

// Location of icon images, exclude trailing slash
// relative to your site_domain
$config['icon_path'] = 'images';

// List config files you would like to exclude
// from showing in the toolbar (without extension).
// Alternatively, set to true to stop all 
// config files from showing.
$config['skip_configs'] = array('database', 'encryption');

// log toolbar data to FirePHP
$config['firephp_enabled'] = TRUE;

$config['panels'] = array(
	'benchmarks'      => TRUE,
	'database'        => TRUE,
	'vars_and_config' => TRUE,
	'logs'            => TRUE,
	'ajax'            => TRUE
);
