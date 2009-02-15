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

$(document).ready(function(){

	/*
	 * Test for javascript libraries
	 * (only supports jQuery at the moment
	 */
	if (typeof jQuery != 'undefined') {
		
		// display ajax button in toolbar
		$('#toggle-ajax').css({display: 'inline'});
		
		// bind ajax event
		$('#debug-ajax').bind("ajaxComplete", function(evt, xmlrequest, ajaxOptions){ 
			
			// add a new row to ajax table
			$('#debug-ajax table').append(
				'<tr class="even">' +
					'<td>' + $('#debug-ajax table tr').size() +'</td>' + 
					'<td>' + ajaxOptions.url + '</td>' +
					'<td>' + xmlrequest.statusText + '</td>' +
				'</tr>'
			);
			
			// stripe table
			$('#debug-ajax table tbody tr:nth-child(even)').attr('class', 'odd');			
		});
		
	}
	
	if (typeof Prototype != 'undefined') {
	}
	
});