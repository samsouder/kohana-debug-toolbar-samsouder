<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Allows the debug toolbar to inject itsself 
 * into the html
 */
Event::add('system.display', array('DebugToolbar', 'render'));
?>