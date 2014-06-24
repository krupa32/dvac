var navigation = {
	init: function() {
		$('#nav_recent').click(function(e){
			recent.show(false, true);
			e.preventDefault();
		});
		$('#nav_assigned').click(function(e){
			caselist.show({ type:'assigned' }, false, true);
			e.preventDefault();
		});
		$('#nav_upcoming_hearings').click(function(e){
			caselist.show({ type:'upcoming_hearings' }, false, true);
			e.preventDefault();
		});
		$('#nav_reminders').click(function(e){
			reminderlist.show(true);
			e.preventDefault();
		});

		navigation.update_case_stats();

	},

	update_case_stats: function() {
		$.get('/case/get_case_stats.php', null, function(data){
			//console.log('nav.update_case_stats recv:' + data);
			var resp = JSON.parse(data);
			$('#num_assigned').text(resp.assigned);
			$('#num_upcoming_hearings').text(resp.upcoming_hearings);
			$('#num_reminders').text(resp.reminders);
		});
	},

	update_hilite: function(type) {

		$('.nav a').removeClass('hilite');

		switch (type) {
		case 'recent':
			$('#nav_recent').addClass('hilite');
			return;
		case 'assigned':
			$('#nav_assigned').addClass('hilite');
			break;
		case 'upcoming_hearings':
			$('#nav_upcoming_hearings').addClass('hilite');
			break;
		case 'reminders':
			$('#nav_reminders').addClass('hilite');
			break;
		}
	}
};
