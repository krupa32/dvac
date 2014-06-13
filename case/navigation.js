var navigation = {
	init: function() {
		$('#nav_recent').click(function(e){
			var arg = { type:'recent' };
			caselist.show(arg, false, true);
			e.preventDefault();
		});

		$('#nav_my').click(function(){
			var arg = { type:'my' };
			caselist.show(arg, false, true);
			return false;
		});
		$('#nav_pending_court').click(function(){
			var arg = { type:'pending_court' };
			caselist.show(arg, false, true);
			return false;
		});
		$('#nav_pending_dvac').click(function(){
			var arg = { type:'pending_dvac' };
			caselist.show(arg, false, true);
			return false;
		});
		$('#nav_upcoming_hearings').click(function(){
			var arg = { type:'upcoming_hearings' };
			caselist.show(arg, false, true);
			return false;
		});
		$('#nav_nohearings').click(function(){
			var arg = { type:'nohearings' };
			caselist.show(arg, false, true);
			return false;
		});

		navigation.update_case_stats();

	},

	update_case_stats: function() {
		$.get('/case/get_case_stats.php', null, function(data){
			//console.log('nav.update_case_stats recv:' + data);
			var resp = JSON.parse(data);
			$('#num_my').text(resp.my);
			$('#num_pending_court').text(resp.pending_court);
			$('#num_pending_dvac').text(resp.pending_dvac);
			$('#num_upcoming_hearings').text(resp.upcoming_hearings);
			$('#num_nohearings').text(resp.nohearings);

			var i, name, key, count, div = $('#categorylist');
			div.html('');
			for (i in resp.categories) {
				name = resp.categories[i].name;
				key = resp.categories[i].key;
				count = resp.categories[i].count;
				div.append('<div class="count" id="num_' + key + '">' + count + 
					'</div><a href="' + name + '" key="' + key + '" id="nav_' + key + '">' + name + '</a>');
			}

			$('#categorylist a').click(function(){
				var arg = { type:'category', name:$(this).attr('href'), key:$(this).attr('key') };
				caselist.show(arg, false, true);
				return false;
			});
		});
	},

	update_hilite: function(arg) {

		$('.nav a').removeClass('hilite');

		switch (arg.type) {
		case 'recent':
		case 'my':
		case 'pending_court':
		case 'pending_dvac':
		case 'upcoming_hearings':
		case 'nohearings':
			$('#nav_' + arg.type).addClass('hilite');
			break;
		case 'category':
			$('#nav_' + arg.key).addClass('hilite');
			break;
		}
	}
};
