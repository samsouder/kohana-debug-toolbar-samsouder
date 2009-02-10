var debugToolbar = {

	// current toolbar section thats open
	current: null,
	
	// current vars and config section open
	currentvar: null,
	
	// current config section open
	currentli: null,
	
	// toggle a toolbar section
	show : function(obj) {
		if (obj == debugToolbar.current) {
			debugToolbar.off(obj);
			debugToolbar.current = null;
		} else {
			debugToolbar.off(debugToolbar.current);
			debugToolbar.on(obj);
			debugToolbar.current = obj;
		}
	},
	
	// toggle a vars and configs section
	showvar : function(li, obj) {
		if (obj == debugToolbar.currentvar) {
			debugToolbar.off(obj);
			debugToolbar.currentli = null;
			debugToolbar.currentli.className = '';
			debugToolbar.currentvar = null;
		} else {
			debugToolbar.off(debugToolbar.currentvar);
			if (debugToolbar.currentli)
				debugToolbar.currentli.className = '';
			debugToolbar.on(obj);
			debugToolbar.currentvar = obj;
			debugToolbar.currentli = li;
			debugToolbar.currentli.className = 'active';
		}
	},
	
	// turn an element on
	on : function(obj) {
		if (document.getElementById(obj) != null)
			document.getElementById(obj).style.display = '';
	},
	
	// turn an element off
	off : function(obj) {
		if (document.getElementById(obj) != null)
			document.getElementById(obj).style.display = 'none';
	},
	
	// toggle an element
	toggle : function(obj) {
		if (document.getElementById(obj) != null)
			if (document.getElementById(obj).style.display == '')
				debugToolbar.off(obj);
			else if (document.getElementById(obj).style.display == 'none')
				debugToolbar.on(obj);
	},
	
	// close the toolbar
	close : function() {
		document.getElementById('debug-toolbar').style.display = 'none';
	}
};