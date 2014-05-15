var navigation = {
	init: function() {
		$('#nav_recent').click(function(){
			recent.show();
			return false;
		});

		$('#nav_open').click(function(){
			search.show('open');
			return false;
		});

		$('#nav_my').click(function(){
			search.show('my');
			return false;
		});

		navigation.update_case_stats();

	},

	update_case_stats: function() {
		$.get('/case/get_case_stats.php', null, function(data){
			//console.log('nav.init recv:' + data);
			var resp = JSON.parse(data);
			$('#nav_num_open_cases').text(resp.open);
			$('#nav_num_my_cases').text(resp.my);
		});
	}
};
