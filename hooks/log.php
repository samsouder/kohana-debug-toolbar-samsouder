<?php

/*
 * Hook system.log event so logs can 
 * be displayed in the dev toolbar
 */
Event::add('system.log', 'devToolbarLog');
function devToolbarLog() {
	DevToolbar::log(Event::$data);
}
?>