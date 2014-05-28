var caselist = {
	init: function() {
		$('#page_caselist a').click(function(e){
			details.show($(this).attr('href'));
			e.preventDefault();
		});
	},

	show: function() {
		$('.page').hide();

		$('#page_caselist').show();
		//$('#activityarea').html('');
		return;

		var param = {};
		$.get('/case/recent.php', param, function(data){
			//console.log('recent.show recv:' + data);
			var resp = JSON.parse(data);
			if (resp.length == 0) {
				$('#activityarea').append('No more recent activities');
				return;
			}

			for (i in resp) {
				switch (resp[i].type) {
				case "ADDCASE":
				case "UPDATECASE":
				case "CLOSE":
					recent.add_case_activity(resp[i]);
					break;
				case "ADDCOMMENT":
					recent.add_comment_activity(resp[i]);
					break;
				case "ADDPROCEEDING":
					recent.add_proceeding_activity(resp[i]);
					break;
				case "ASSIGN":
					recent.add_assignment_activity(resp[i]);
					break;
				}
			}

			$('#page_recent a').click(function(e){
				details.show($(this).attr('href'));
				history.pushState('', '', '/case/' + $(this).attr('href'));
				e.preventDefault();
			});

			$('#page_recent').show();
		});

	},

	add_case_activity: function(resp) {
		var div = $('<div class="activity"></div>').appendTo('#activityarea');
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

		div.append('<p class="title">' + resp.doer + action + '<a href="' + resp.case_id + '">' + 
				resp.details.case_num + '</a></p>');
		div.append('<p class="extra">Petitioner ' + resp.details.petitioner + '<br>' + 
			'Respondent ' + resp.details.respondent + '</p>');
		div.append('<p class="text">' + resp.details.prayer + '</p>');
	},

	add_comment_activity: function(resp) {
		var div = $('<div class="activity"></div>').appendTo('#activityarea');
		div.append('<p class="title floatright">' + resp.ts + '</p>');
		div.append('<p class="title">' + resp.doer + ' commented on case '+ '<a href="' + resp.case_id + '">' + 
				resp.details.case_num + '</a></p>');
		div.append('<p class="extra">Petitioner ' + resp.details.petitioner + '<br>' + 
			'Respondent ' + resp.details.respondent + '</p>');
		div.append('<p class="text">' + resp.details.comment + '</p>');
	},

	add_proceeding_activity: function(resp) {
		var div = $('<div class="activity"></div>').appendTo('#activityarea');
		div.append('<p class="title floatright">' + resp.ts + '</p>');
		div.append('<p class="title">' + resp.doer + ' updated a proceeding for case '+ '<a href="' + resp.case_id + '">' + 
				resp.details.case_num + '</a></p>');
		div.append('<p class="extra">Petitioner ' + resp.details.petitioner + '<br>' + 
			'Respondent ' + resp.details.respondent + '<br>' + 
			'At Hall ' + resp.details.hall + ', ' + resp.details.court + ' by Judge ' + resp.details.judge + '<br>' + 
			'Counsel ' + resp.details.counsel + ' appeared<br>' + 
			'Disposal ' + resp.details.disposal + '</p>');
		div.append('<p class="text">' + resp.details.comment + '</p>');
	},

	add_assignment_activity: function(resp) {
		var div = $('<div class="activity"></div>').appendTo('#activityarea');
		div.append('<p class="title floatright">' + resp.ts + '</p>');
		div.append('<p class="title">' + resp.doer + ' assigned case '+ '<a href="' + resp.case_id + '">' + 
				resp.details.case_num + '</a> to ' + resp.details.target + '</p>');
		div.append('<p class="extra">Petitioner ' + resp.details.petitioner + '<br>' + 
			'Respondent ' + resp.details.respondent + '</p>');
		div.append('<p class="text">' + resp.details.comment + '</p>');
	}
};
