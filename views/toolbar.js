var debugToolbar = {

	current: null,
	currentvar: null,
	currentli: null,
	
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
	
	on : function(obj) {
		if (document.getElementById(obj) != null)
			document.getElementById(obj).style.display = '';
	},
	
	off : function(obj) {
		if (document.getElementById(obj) != null)
			document.getElementById(obj).style.display = 'none';
	},
	
	toggle : function(obj) {
		if (document.getElementById(obj) != null)
			if (document.getElementById(obj).style.display == '')
				debugToolbar.off(obj);
			else if (document.getElementById(obj).style.display == 'none')
				debugToolbar.on(obj);
	},
	close : function() {
		document.getElementById('debug-toolbar').style.display = 'none';
	}
};