<?php
/*
 * Hook system.log event so logs can 
 * be displayed in the debug toolbar
 */
Event::add('system.log', 'debugToolbarLog');
function debugToolbarLog() {
	DebugToolbar::log(Event::$data);
}
?>