var dashboard = {

	init: function() {
		//$('#global .section').click(function(){
		//	caselist.show({ type:$(this).attr('type') }, false, true);
		//});
		$('#range .section').click(function(){
			caselist.show({ type:$(this).attr('type') }, false, true);
		});
		$('#hearing p').click(function(){
			caselist.show({ type:$(this).attr('type') }, false, true);
		});
	},

	show: function(push) {

		$('.page').hide();
		$('#page_dashboard').show();
		navigation.update_hilite('dashboard');

		if (push)
			history.pushState({ page:'dashboard' }, '', '#dashboard');

		$.get('/case/dashboard.php', null, function(data){

			var resp = JSON.parse(data);
			console.log('dashboard.show recv ' + data.length + 'b, query took ' + resp.latency + ' ms');
			//console.log('data:' + data);

			dashboard.update_global(resp.global);
			dashboard.update_range(resp.range);
			dashboard.update_category(resp.category);
			dashboard.update_location(resp.location);
			dashboard.update_hearing(resp.hearing);
			dashboard.update_team(resp.team);
		});
	},

	update_global: function(g) {
		$('#global_num_total').text(g['TOTAL']);
		$('#global_num_pending_court').text(g['PENDING_IN_COURT']);
		$('#global_num_pending_dvac').text(g['PENDING_WITH_DVAC']);
		$('#global_num_closed').text(g['CLOSED']);
	},

	update_range: function(r) {
		$('#range_num_total').text(r['TOTAL']);
		$('#range_num_pending_court').text(r['PENDING_IN_COURT']);
		$('#range_num_pending_dvac').text(r['PENDING_WITH_DVAC']);
		$('#range_num_closed').text(r['CLOSED']);
	},

	update_category: function(c) {
		var key;
		$('#dash_category').html('');
		for (key in c)
			$('#dash_category').append('<div class="count">' + c[key] + '</div><p>' + key + '</p>');

		$('#dash_category p').click(function(){
			caselist.show({ type:'category', value:$(this).text() }, false, true);
		});
	},

	update_location: function(l) {
		var key;
		$('#dash_location').html('');
		for (key in l)
			$('#dash_location').append('<div class="count">' + l[key] + '</div><p>' + key + '</p>');

		$('#dash_location p').click(function(){
			caselist.show({ type:'location', value:$(this).text() }, false, true);
		});
	},

	update_hearing: function(h) {
		$('#hearing_num_upcoming').text(h['upcoming']);
		$('#hearing_num_notspecified').text(h['notspecified']);
	},

	update_team: function(t) {
		$('#dash_team').html('');
		dashboard.add_to_team($('#dash_team'), t);

		$('#dash_team p').click(function(){
			caselist.show({ type:'user', value:$(this).attr('id') }, false, true);
		});
	},

	add_to_team: function(ul, t) {
		var i, li, new_ul;

		for (i in t) {
			li = $('<li></li>').appendTo(ul);
			li.append('<div class="count">' + t[i].count + '</div><p id="' + t[i].id + '">' + t[i].name + '</p>');
			if (t[i].team) {
				new_ul = $('<ul></ul>').appendTo(li);
				dashboard.add_to_team(new_ul, t[i].team);
			}
		}
	}


};
