var recent = {

	/* starting item for next fetch.
	 * during each fetch, the number of items specified
	 * in config.php is returned. this value is incremented
	 * by the num items returned by the server.
	 */
	start_item: 0,

	init: function() {
		$('#recent_more').click(function(){
			recent.show(true, false);
		});
	},

	show: function(more, push) {

		if (!more) {
			recent.start_item = 0;
			$('.page').hide();
			$('#page_recent').show();
			$('#recent_more').show();
			navigation.update_hilite('recent');
		}

		if (push)
			history.pushState({ page:'recent' }, '', '#recent');

		/* send start_item to the server */
		var param = {};
		param.start_item = recent.start_item;

		$.get('/case/recent.php', param, function(data){
			if (!more)
				$('#recentarea').html('');

			var resp = JSON.parse(data);
			var cases = resp.cases;
			console.log('recent.show recv ' + data.length + 'b, query took ' + resp.latency + ' ms');
			//console.log('data:' + data);

			if (cases.length == 0) {
				$('#recentarea').append('<div class="aligncenter nomore">No more items found</div>');
				$('#recent_more').hide();
				return;
			}

			if (cases.length < 10) {
				/* WARNING: currently this number is hard-coded.
				 * If num_items_per_fetch is changed in config.php,
				 * this number should also be updated.
				 */
				$('#recent_more').hide();
			}

			for (i in cases)
				recent.add_case(cases[i]);

			recent.start_item += cases.length;

			$('#page_recent a.caselink').click(function(e){
				details.show($(this).attr('href'), true);
				e.preventDefault();
			});

		});
	},

	add_case: function(c) {
		// trim the petitioner, respondent
		if (c.petitioner.length > 60)
			c.petitioner = c.petitioner.substr(0, 60) + '...';
		if (c.respondent.length > 60)
			c.respondent = c.respondent.substr(0, 60) + '...';

		var divcase = $('<div class="case"></div>').appendTo('#recentarea');
		divcase.append('<p class="casenum"><a class="caselink" href="' + c.id + '">' + c.case_num + '</a></p>');
		var extra = '<p class="extra">Petitioner ' + c.petitioner + ', Respondent ' + c.respondent + '<br>';
		extra += 'Next hearing ' + c.next_hearing;
		extra += '</p>';
		divcase.append(extra);
		//divcase.append('<p class="text">' + c.prayer + '</p>');

		var divact = $('<div class="activityarea"></div>').appendTo(divcase);
		for (i in c.activities)
			recent.add_activity(divact, c.activities[i]);
	},

	add_activity: function(divact, a) {

		switch (a.type) {
		case "ADDCASE":
		case "UPDATECASE":
		case "CLOSE":
			recent.add_case_activity(divact, a);
			break;
		case "ADDCOMMENT":
			recent.add_comment_activity(divact, a);
			break;
		case "ADDPROCEEDING":
			recent.add_proceeding_activity(divact, a);
			break;
		case "ASSIGN":
			recent.add_assignment_activity(divact, a);
			break;
		case "CHANGESTATUS":
			recent.add_change_status_activity(divact, a);
			break;
		case "ATTACH":
			recent.add_attachment_activity(divact, a);
			break;
		}

	},

	add_case_activity: function(divact, a) {
		var div = $('<div class="activity"></div>').appendTo(divact);
		div.append('<p class="title floatright">' + a.ts + '</p>');

		var action;
		switch (a.type) {
		case "ADDCASE":
			action = ' added case';
			break;
		case "UPDATECASE":
			action = ' updated case';
			break;
		case "CLOSE":
			action = ' closed case';
			break;
		}

		div.append('<p class="title">' + a.doer + action + '</p>');
	},

	add_comment_activity: function(divact, a) {
		var div = $('<div class="activity"></div>').appendTo(divact);
		div.append('<p class="title floatright">' + a.ts + '</p>');
		div.append('<p class="title">' + a.doer + ' commented on case</p>');
		div.append('<p class="text">' + a.details.comment + '</p>');
	},

	add_proceeding_activity: function(divact, a) {
		var div = $('<div class="activity"></div>').appendTo(divact);
		div.append('<p class="title floatright">' + a.ts + '</p>');
		div.append('<p class="title">' + a.doer + ' updated a proceeding for case</p>');
		extra = '<p class="extra">' + 
			'At Hall ' + a.details.hall + ', ' + a.details.court + ' High Court, by Justice ' + a.details.judge + 
			', Counsel ' + a.details.counsel + ' appeared, <br>' + 
			'Disposal ' + a.details.disposal + ', Next hearing ' + a.details.next_hearing;
		if (a.details.comment.length > 0)
			extra += ', ' + a.details.comment;
		extra += '</p>';
		div.append(extra);
		//div.append('<p class="text">' + a.details.comment + '</p>');
	},

	add_assignment_activity: function(divact, a) {
		var div = $('<div class="activity"></div>').appendTo(divact);
		div.append('<p class="title floatright">' + a.ts + '</p>');
		div.append('<p class="title">' + a.doer + ' assigned case to ' + a.details.target + '</p>');
		div.append('<p class="text">' + a.details.comment + '</p>');
	},

	add_change_status_activity: function(divact, a) {
		var div = $('<div class="activity"></div>').appendTo(divact);
		div.append('<p class="title floatright">' + a.ts + '</p>');
		div.append('<p class="title">' + a.doer + ' changed case status to ' + a.details.status + '</p>');
	},

	add_attachment_activity: function(divact, a) {
		var div = $('<div class="activity"></div>').appendTo(divact);
		div.append('<p class="title floatright">' + a.ts + '</p>');
		div.append('<p class="title">' + a.doer + ' added an attachment ' + 
				'<a href="' + a.details.link + '">' + a.details.name + '</a></p>');
	}





};