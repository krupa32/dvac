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
		$('#details_edit').click(function(){
			editcase.show($('#page_details').data('id'), true);
		});
		$('#details_close').click(function(){
			if (!confirm('Do you really want to close this case?'))
				return;
			closecase.show($('#page_details').data('id'));
		});
		$('#details_change').click(function(e){
			changestatus.show($('#page_details').data('id'));
			e.preventDefault();
		});
		$('#details_dvac_change').click(function(e){
			changedvacstatus.show($('#page_details').data('id'));
			e.preventDefault();
		});
		$('#btn_attach').click(function(){
			attach.show($('#page_details').data('id'));
		});
		$('#btn_addreminder').click(function(){
			addreminder.show($('#page_details').data('id'));
		});

		if (!(user_caps & CAP_CHANGECASESTATUS)) {
			$('#details_change').hide();
			$('#details_dvac_change').hide();
		}

		if (!(user_caps & CAP_EDITCASE))
			$('#details_edit').attr('disabled', true);
		if (!(user_caps & CAP_ATTACH))
			$('#btn_attach').attr('disabled', true);
		if (!(user_caps & CAP_ADDPROCEEDING))
			$('#btn_addproceeding').attr('disabled', true);
		if (!(user_caps & CAP_COMMENT))
			$('#btn_addcomment').attr('disabled', true);
		if (!(user_caps & CAP_ASSIGN))
			$('#btn_assign').attr('disabled', true);
		if (!(user_caps & CAP_ADDREMINDER))
			$('#btn_addreminder').attr('disabled', true);

	},

	show: function(id, push) {
		$('.page').hide();
		$('#page_details').data('id', id);

		if (push)
			history.pushState({ page:'details', id:id }, '', '#details/' + id);

		$('.ajaxstatus').text('Loading...').show();
		var param = {};
		param.id = id;
		$.get('/case/get_details.php', param, function(data){
			//console.log('details.show recv:' + data);
			$('.ajaxstatus').text('Done').fadeOut();
			var resp = JSON.parse(data);

			if (resp.tag == '')
				resp.tag = 'None';

			$('#details_case_num').text(resp.case_num);
			$('#details_status').text(resp.status);
			$('#details_dvac_status').text(resp.dvac_status);
			$('#details_io').text(resp.investigator);
			$('#details_assigned_to').text(resp.assigned_to);
			$('#details_petitioner').text(resp.petitioner);
			$('#details_respondent').text(resp.respondent);
			$('#details_prayer').text(resp.prayer);
			$('#details_next_hearing').text(resp.next_hearing);
			$('#details_tag').text(resp.tag);
			$('#details_direction').text(resp.direction);

			// set back color of status
			$('#details_status').removeClass('red green gray');
			if (resp.status == 'OPEN')
				$('#details_status').addClass('green');
			else 
				$('#details_status').addClass('gray');

			// set back color of dvac status
			$('#details_dvac_status').removeClass('red green gray');
			if (resp.dvac_status == 'DVAC_OPEN')
				$('#details_dvac_status').addClass('red');
			else 
				$('#details_dvac_status').addClass('gray');


			details.show_history(id);

			/* if case is closed, all buttons are disabled */
			if (resp.status == 'CLOSED' && resp.dvac_status == 'DVAC_CLOSED')
				$('#page_details button').attr('disabled', true).removeClass('primary');
			//else
			//	$('#page_details button').attr('disabled', false).addClass('primary');

			$('#page_details').show();
		});
	},

	show_history: function(id) {
		var param = {};
		param.id = id;
		$.get('/case/history.php', param, function(data){
			//console.log('show_history recv:' + data);
			$('#historyarea').html('');
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
				case "CHANGESTATUS":
					details.add_change_status_activity(resp[i]);
					break;
				case "CHANGEDVACSTATUS":
					details.add_change_dvac_status_activity(resp[i]);
					break;
				case "ATTACH":
					details.add_attachment_activity(resp[i]);
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
		var extra = '<p class="extra">' + 
			'At Hall ' + resp.details.hall + ', ' + resp.details.court + ' High Court, by ' + resp.details.judge +
			', Counsel ' + resp.details.counsel + ' appeared,<br>' + 
			'Disposal ' + resp.details.disposal + ', Next hearing ' + resp.details.next_hearing;
		extra += '</p>';
		div.append(extra);
		div.append('<p class="text">' + resp.details.comment + '</p>');
	},

	add_assignment_activity: function(resp) {
		var div = $('<div class="activity"></div>').appendTo('#historyarea');
		div.append('<p class="title floatright">' + resp.ts + '</p>');
		div.append('<p class="title">' + resp.doer + ' assigned case to ' + resp.details.target + '</p>');
		div.append('<p class="text">' + resp.details.comment + '</p>');
	},

	add_change_status_activity: function(resp) {
		var div = $('<div class="activity"></div>').appendTo('#historyarea');
		div.append('<p class="title floatright">' + resp.ts + '</p>');
		div.append('<p class="title">' + resp.doer + ' changed case status to ' + resp.details.status + '</p>');
	},

	add_change_dvac_status_activity: function(resp) {
		var div = $('<div class="activity"></div>').appendTo('#historyarea');
		div.append('<p class="title floatright">' + resp.ts + '</p>');
		div.append('<p class="title">' + resp.doer + ' changed dvac status to ' + resp.details.dvac_status + '</p>');
	},

	add_attachment_activity: function(resp) {
		var div = $('<div class="activity"></div>').appendTo('#historyarea');
		div.append('<p class="title floatright">' + resp.ts + '</p>');
		div.append('<p class="title">' + resp.doer + ' added ' + resp.details.type + ' attachment ' + 
				'<a href="' + resp.details.link + '" target="_blank">' + resp.details.name + '</a></p>');
		div.append('<p class="text">' + resp.details.comment + '</p>');
	}


};
