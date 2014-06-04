var navigation = {
	init: function() {
		$('#nav_recent').click(function(e){
			var arg = { type:'recent' };
			caselist.show(arg, true);
			e.preventDefault();
		});

		$('#nav_my').click(function(){
			var arg = { type:'my' };
			caselist.show(arg, true);
			return false;
		});
		$('#nav_pending_court').click(function(){
			var arg = { type:'pending_court' };
			caselist.show(arg, true);
			return false;
		});
		$('#nav_pending_dvac').click(function(){
			var arg = { type:'pending_dvac' };
			caselist.show(arg, true);
			return false;
		});
		$('#nav_category_crlop').click(function(){
			var arg = { type:'crlop' };
			caselist.show(arg, true);
			return false;
		});
		$('#nav_category_wp').click(function(){
			var arg = { type:'wp' };
			caselist.show(arg, true);
			return false;
		});
		$('#nav_category_wa').click(function(){
			var arg = { type:'wa' };
			caselist.show(arg, true);
			return false;
		});
		$('#nav_category_rc').click(function(){
			var arg = { type:'rc' };
			caselist.show(arg, true);
			return false;
		});
		$('#nav_category_ca').click(function(){
			var arg = { type:'ca' };
			caselist.show(arg, true);
			return false;
		});
		$('#nav_hearings').click(function(){
			var arg = { type:'upcoming_hearings' };
			caselist.show(arg, true);
			return false;
		});

		navigation.update_case_stats();

	},

	update_case_stats: function() {
		$.get('/case/get_case_stats.php', null, function(data){
			console.log('nav.update_case_stats recv:' + data);
			var resp = JSON.parse(data);
			$('#num_my').text(resp.my);
			$('#num_pending_court').text(resp.pending_court);
			$('#num_pending_dvac').text(resp.pending_dvac);
			$('#num_hearings').text(resp.hearings);
			$('#num_crlop').text(resp.crlop);
			$('#num_wp').text(resp.wp);
			$('#num_wa').text(resp.wa);
			$('#num_rc').text(resp.rc);
			$('#num_ca').text(resp.ca);
		});
	}
};
