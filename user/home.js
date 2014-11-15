var home = {
	init: function() {
		/* get grades */
		utils.dynamic_combo('#user_grade', '/common/get_grades.php', function(){
			/* get locations */
			utils.dynamic_combo('#user_location', '/common/get_locations.php', function(){
				/* get user details and fill */
				$.get('/user/get_details.php', null, function(data){
					//console.log('get_details: recv: ' + data);
					var resp = JSON.parse(data);
					$('#user_name').val(resp.name);
					$('#user_grade').val(resp.grade);
					$('#user_rep_officer').data('id', resp.reporting_to);
					if (resp.reporting_to != '0')
						$('#user_rep_officer').val(resp.reporting_to_name);
					$('#user_location').val(resp.location);
					$('#user_phone').val(resp.phone);
				});
			});
		});

		$('#user_rep_officer').autocomplete({
			source: '/common/get_user_autocomplete.php',
			select: function(event,ui) {
				$('#user_rep_officer').val(ui.item.label).data('id', ui.item.value);
				return false;
			}
		});

		$('#user_rep_officer').blur(function(){
			if ($(this).val() == '')
				$(this).data('id', 0);
		});

		$('#btn_save').click(home.save);
		$('#btn_changepassword').click(home.change_password);

	},

	save: function() {
		var param = {};
		param.name = $('#user_name').val();
		param.grade = $('#user_grade').val();
		param.reporting_to = $('#user_rep_officer').data('id');
		param.location = $('#user_location').val();
		param.phone = $('#user_phone').val();
		$.post('/user/save.php', param, function(data){
			//console.log('save recv:' + data);
			var resp = JSON.parse(data);
			if (resp != "ok") {
				alert('Error: ' + resp);
			} else
				$('#status').text('User information updated succesfully');
		});
	},

	change_password: function() {
		if ($('#user_password').val() != $('#user_confirm').val()) {
			alert('Passwords dont match');
			return;
		}
		var param = {};
		param.password = $('#user_password').val();
		$.post('/user/change_password.php', param, function(data){
			//console.log('change_password recv:' + data);
			var resp = JSON.parse(data);
			if (resp != "ok") {
				alert('Error: ' + resp);
			} else
				$('#status').text('User password changed succesfully');
		});

	}
};
