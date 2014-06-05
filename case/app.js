var app = {
	init: function() {
		window.onpopstate = function(h) {
			if (h.state)
				app.popstate(h.state);
		};
	},
	
	popstate: function(state) {
		console.log('popstate:' + JSON.stringify(state));

		if (state.page == 'caselist')
			caselist.show(state.arg, false);
		else if (state.page == 'editcase')
			editcase.show(state.id, false);
		else if (state.page == 'details')
			details.show(state.id, false);
	}
};
