var app = {
	init: function() {
		window.onpopstate = function(h) {
			if (h.state)
				app.popstate(h.state);
		};

		var left = $(window).width()/2 - $('.ajaxstatus').width()/2; 
		$('.ajaxstatus').css('left', left).css('top', 25);

		$(document).ajaxError(function(evt, xhr, opt, e){
			alert('Error (' + opt.url + '): ' + xhr.status + ' ' + xhr.statusText);
			$('.ajaxstatus').hide();
		});

		/* when any button is clicked, disable it immediately
		 * for few secs. this will prevent unintentional multiple
		 * clicks caused by old mouses.
		 */
		$('button').click(function(){
			var b = this;
			$(b).attr('disabled', true);
			setTimeout(function(){ $(b).attr('disabled', false); }, 2000);
		});
	},
	
	popstate: function(state) {
		//console.log('popstate:' + JSON.stringify(state));

		if (state.page == 'recent')
			recent.show(false, false);
		else if (state.page == 'caselist')
			caselist.show(state.arg, false, false);
		else if (state.page == 'editcase')
			editcase.show(state.id, false);
		else if (state.page == 'details')
			details.show(state.id, false);
		else if (state.page == 'reminderlist')
			reminderlist.show(false);
		else if (state.page == 'dashboard')
			dashboard.show(false);
		else if (state.page == 'advancedsearch')
			advancedsearch.show(false);
	}
};
