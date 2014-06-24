var reminderlist = {

	init: function() {
	},

	show: function(push) {

		$('.page').hide();
		$('#page_reminderlist').show();
		navigation.update_hilite('reminders');

		if (push)
			history.pushState({ page:'reminderlist' }, '', '#reminderlist');

		$.get('/case/reminderlist.php', null, function(data){
			$('#reminderlistarea').html('');

			var resp = JSON.parse(data);
			//console.log('reminderlist.show recv ' + data.length + 'b:' + data);

			if (resp.length == 0) {
				$('#reminderlistarea').append('<div class="aligncenter rl_data">No reminders found</div>');
				return;
			}

			for (i in resp)
				reminderlist.add_reminder(resp[i]);

			$('#page_reminderlist a.caselink').click(function(e){
				details.show($(this).attr('href'), true);
				e.preventDefault();
			});

		});
	},

	add_reminder: function(r) {

		var div = $('<div class="rl_data"></div>').appendTo('#reminderlistarea');
		div.append('<div class="rl_case"><a class="caselink" href="' + r.id + '">' + r.case_num + '</a></div>');
		div.append('<div class="rl_comment">' + r.comment+ '</div>');
		div.append('<div class="rl_remind_on">' + r.remind_on+ '</div>');

		if (r.remind_on == 'Today')
			div.find('.rl_comment, .rl_remind_on').addClass('red');
	}

};
