var details = {
	init: function() {
		$('#btn_addproceeding').click(function(){
			addproceeding.show($('#page_details').data('id'));
		});
		$('#btn_addcomment').click(function(){
			addcomment.show($('#page_details').data('id'));
		});
		$('#btn_assign').click(function(){
			assign.show($('#page_details').data('id'));
		});
		$('#details_update').click(function(){
			editcase.show($('#page_details').data('id'));
		});
		$('#details_close').click(function(){
			if (!confirm('Do you really want to close this case?'))
				return;
			closecase.show($('#page_details').data('id'));
		});
	},

	show: function(id) {
		$('.page').hide();
		$('#page_details').data('id', id);

		var param = {};
		param.id = id;
		$.get('/case/get_details.php', param, function(data){
			//console.log('details.show recv:' + data);
			var resp = JSON.parse(data);
			$('#details_case_num').text(resp.case_num);
			$('#details_status').text(resp.status);
			$('#details_io').text(resp.investigator);
			$('#details_assigned_to').text(resp.assigned_to);
			$('#details_petitioner').text(resp.petitioner);
			$('#details_respondent').text(resp.respondent);
			$('#details_prayer').text(resp.prayer);

			details.show_history(id);

			/* if case is closed, all buttons are disabled */
			if (resp.status == 'CLOSED')
				$('#page_details button').attr('disabled', true);
			else
				$('#page_details button').attr('disabled', false);

			$('#page_details').show();
		});
	},

	show_history: function(id) {
		$('#historyarea').html('');
		var param = {};
		param.id = id;
		$.get('/case/history.php', param, function(data){
			//console.log('show_history recv:' + data);
			var resp = JSON.parse(data);
			if (resp.length == 0) {
				$('#historyarea').append('No history');
				return;
			}

			for (i in resp) {
				switch (resp[i].type) {
				case "ADDCASE":
				case "UPDATECASE":
				case "CLOSE":
					details.add_case_activity(resp[i]);
					break;
				case "ADDCOMMENT":
					details.add_comment_activity(resp[i]);
					break;
				case "ADDPROCEEDING":
					details.add_proceeding_activity(resp[i]);
					break;
				case "ASSIGN":
					details.add_assignment_activity(resp[i]);
					break;
				}
			}

		});
	},
	
	add_case_activity: function(resp) {
		var div = $('<div class="activity"></div>').appendTo('#historyarea');
		div.append('<p class="title floatright">' + resp.ts + '</p>');

		var action;
		switch (resp.type) {
		case "ADDCASE":
			action = ' added case ';
			break;
		case "UPDATECASE":
			action = ' updated case ';
			break;
		case "CLOSE":
			action = ' closed case ';
			break;
		}

		div.append('<p class="title">' + resp.doer + action + '</p>');
	},

	add_comment_activity: function(resp) {
		var div = $('<div class="activity"></div>').appendTo('#historyarea');
		div.append('<p class="title floatright">' + resp.ts + '</p>');
		div.append('<p class="title">' + resp.doer + ' commented on case</p>');
		div.append('<p class="text">' + resp.details.comment + '</p>');
	},

	add_proceeding_activity: function(resp) {
		var div = $('<div class="activity"></div>').appendTo('#historyarea');
		div.append('<p class="title floatright">' + resp.ts + '</p>');
		div.append('<p class="title">' + resp.doer + ' updated a proceeding for case</p>');
		div.append('<p class="extra">' + 
			'At Hall ' + resp.details.hall + ', ' + resp.details.court + ' by Judge ' + resp.details.judge + '<br>' + 
			'Counsel ' + resp.details.counsel + ' appeared<br>' + 
			'Disposal ' + resp.details.disposal + '</p>');
		div.append('<p class="text">' + resp.details.comment + '</p>');
	},

	add_assignment_activity: function(resp) {
		var div = $('<div class="activity"></div>').appendTo('#historyarea');
		div.append('<p class="title floatright">' + resp.ts + '</p>');
		div.append('<p class="title">' + resp.doer + ' assigned case to ' + resp.details.target + '</p>');
		div.append('<p class="text">' + resp.details.comment + '</p>');
	}

};
