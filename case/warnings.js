var warnings = {
	init: function() {

		$('.warnings').hide();

		$.get('/case/dashboard.php', null, function(data){
			//console.log('get_warnings recv:' + data);
			var resp = JSON.parse(data);
			var show_warnings = false;

			if (resp.hearing['notupdated'] != 0) {
				$('#warnings_text').html('You have ' + resp.hearing['notupdated'] + 
					' cases under your range for which proceedings have not been updated. Please check the dashboard.<br>');
				show_warnings = true;
			}

			if (show_warnings)
				$('.warnings').slideDown('slow');
		});

		$('#warnings_close').click(function(){
			$('.warnings').slideUp('slow');
		});
	}
};
