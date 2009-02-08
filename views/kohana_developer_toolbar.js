var devToolbar = {

	current: null,
	currentvar: null,
	
	show : function(obj) {
		if (obj == devToolbar.current) {
			devToolbar.off(obj);
			devToolbar.current = null;
		} else {
			devToolbar.off(devToolbar.current);
			devToolbar.on(obj);
			devToolbar.current = obj;
		}
	},
	
	showvar : function(obj) {
		if (obj == devToolbar.currentvar) {
			devToolbar.off(obj);
			devToolbar.currentvar = '';
		} else {
			devToolbar.off(devToolbar.currentvar);
			devToolbar.on(obj);
			devToolbar.currentvar = obj;
		}
	},
	
	on : function(obj) {
		if (document.getElementById(obj) == null)
			return;
		else
			document.getElementById(obj).style.display = '';
	},
	
	off : function(obj) {
		if (document.getElementById(obj) == null)
			return;
		else
			document.getElementById(obj).style.display = 'none';
	},
	
	toggle : function(obj) {
		if (document.getElementById(obj) == null)
			return;
		else
			if (document.getElementById(obj).style.display == '')
				devToolbar.off(obj);
			else if (document.getElementById(obj).style.display == 'none')
				devToolbar.on(obj);
	},
	close : function() {
		document.getElementById('dev-toolbar').style.display = 'none';
	}
};