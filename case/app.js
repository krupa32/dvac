var app = {
	init: function() {
		window.onpopstate = function(e) {
			console.log('popstate:' + JSON.stringify(e.state));
			if (!e.state)
				return;

			if (e.state.page == 'caselist')
				caselist.show(e.state.arg, false);
			else if (e.state.page == 'editcase')
				editcase.show(e.state.id, false);
			else if (e.state.page == 'details')
				details.show(e.state.id, false);
		};
	}
};
