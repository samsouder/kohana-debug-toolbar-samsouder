var debugToolbar = {

	current: null,
	currentvar: null,
	
	show : function(obj) {
		if (obj == debugToolbar.current) {
			debugToolbar.off(obj);
			debugToolbar.current = null;
		} else {
			debugToolbar.off(debugToolbar.current);
			debugToolbar.on(obj);
			debugToolbar.current = obj;
		}
		return false;
	},
	
	showvar : function(obj) {
		if (obj == debugToolbar.currentvar) {
			debugToolbar.off(obj);
			debugToolbar.currentvar = '';
		} else {
			debugToolbar.off(debugToolbar.currentvar);
			debugToolbar.on(obj);
			debugToolbar.currentvar = obj;
		}
		return false;
	},
	
	on : function(obj) {
		if (document.getElementById(obj) != null)
			document.getElementById(obj).style.display = '';
		return false;
	},
	
	off : function(obj) {
		if (document.getElementById(obj) != null)
			document.getElementById(obj).style.display = 'none';
		return false;
	},
	
	toggle : function(obj) {
		if (document.getElementById(obj) != null)
			if (document.getElementById(obj).style.display == '')
				debugToolbar.off(obj);
			else if (document.getElementById(obj).style.display == 'none')
				debugToolbar.on(obj);
		return false;
	},
	close : function() {
		document.getElementById('debug-toolbar').style.display = 'none';
	}
};