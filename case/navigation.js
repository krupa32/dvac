var navigation = {
	init: function() {
		$('#nav_recent').click(function(e){
			$('.nav a').removeClass('hilite');
			$(this).addClass('hilite');
			var arg = { type:'recent' };
			caselist.show(arg, false, true);
			e.preventDefault();
		});

		$('#nav_my').click(function(){
			$('.nav a').removeClass('hilite');
			$(this).addClass('hilite');
			var arg = { type:'my' };
			caselist.show(arg, false, true);
			return false;
		});
		$('#nav_pending_court').click(function(){
			$('.nav a').removeClass('hilite');
			$(this).addClass('hilite');
			var arg = { type:'pending_court' };
			caselist.show(arg, false, true);
			return false;
		});
		$('#nav_pending_dvac').click(function(){
			$('.nav a').removeClass('hilite');
			$(this).addClass('hilite');
			var arg = { type:'pending_dvac' };
			caselist.show(arg, false, true);
			return false;
		});
		$('#nav_hearings').click(function(){
			$('.nav a').removeClass('hilite');
			$(this).addClass('hilite');
			var arg = { type:'upcoming_hearings' };
			caselist.show(arg, false, true);
			return false;
		});
		$('#nav_nohearings').click(function(){
			$('.nav a').removeClass('hilite');
			$(this).addClass('hilite');
			var arg = { type:'no_hearings' };
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
			$('#num_hearings').text(resp.hearings);
			$('#num_nohearings').text(resp.nohearings);

			var i, name, key, count, div = $('#categorylist');
			div.html('');
			for (i in resp.categories) {
				name = resp.categories[i].name;
				key = resp.categories[i].key;
				count = resp.categories[i].count;
				div.append('<div class="count" id="num_' + key + '">' + count + 
					'</div><a href="' + name + '" id="nav_' + key + '">' + name + '</a>');
			}

			$('#categorylist a').click(function(){
				$('.nav a').removeClass('hilite');
				$(this).addClass('hilite');
				var arg = { type:'category', name:$(this).attr('href') };
				caselist.show(arg, false, true);
				return false;
			});
		});
	}
};
