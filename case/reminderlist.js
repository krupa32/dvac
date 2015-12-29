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

			$('#page_reminderlist a.dismisslink').click(function(e){
				reminderlist.dismiss($(this).attr('href'));
				e.preventDefault();
			});
		});
	},

	add_reminder: function(r) {

		var div = $('<div class="rl_data"></div>').appendTo('#reminderlistarea');
		div.append('<div class="rl_case"><a class="caselink" href="' + r.case_id + '">' + r.case_num + '</a></div>');
		div.append('<div class="rl_comment">' + r.comment+ '</div>');
		div.append('<div class="rl_remind_on">' + r.remind_on+ '</div>');
		div.append('<div class="rl_action"><a class="dismisslink" href="' + r.id + '">Dismiss</a></div>');

		if (r.expired)
			div.find('.rl_comment, .rl_remind_on').addClass('red');
	},

	dismiss: function(id) {
		var param = {};
		param.id = id;

		$('.ajaxstatus').text('Dismissing reminder...').show();
		$.post('/case/dismiss_reminder.php', param, function(data){
			//console.log('reminderlist.dismiss recv:' + data);
			var resp = JSON.parse(data);
			if (resp != "ok") {
				alert('Error:' + resp);
				$('.ajaxstatus').hide();
				return;
			}

			$('.ajaxstatus').text('Reminder dismissed').fadeOut();

			navigation.update_case_stats();

			reminderlist.show(false);
		});

	}

};
