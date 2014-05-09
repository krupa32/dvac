var home = {
	init: function() {
		/* get grades */
		utils.dynamic_combo('#user_grade', '/common/get_grades.php', function(){
			/* get locations */
			utils.dynamic_combo('#user_location', '/common/get_locations.php', function(){
				/* get user details and fill */
				$.get('/user/get_details.php', null, function(data){
					var resp = JSON.parse(data);
					$('#user_name').val(resp.name);
					$('#user_grade').val(resp.grade);
					$('#user_rep_officer').val(resp.reporting_officer);
					$('#user_rep_officer').data('id', resp.reporting_officer_id);
					$('#user_location').val(resp.location);
				});
			});
		});

		$('#user_rep_officer').autocomplete({
			source: '/common/get_user_autocomplete.php'
		});

		$('#btn_save').click(home.save);
		$('#btn_changepassword').click(home.change_password);

	},

	save: function() {
		var param = {};
		param.name = $('#user_name').val();
		param.grade = $('#user_grade').val();
		param.reporting_officer_id = $('#user_rep_officer').data('id');
		param.location = $('#user_location').val();
		$.post('/user/save.php', param, function(data){
			var resp = JSON.parse(data);
			if (resp != "ok") {
				alert('Error: ' + resp);
			} else
				$('#status').text('User information updated succesfully');
		});
	},

	change_password: function() {
		var param = {};
		param.password = $('#user_password').val();
		$.post('/user/change_password.php', param, function(data){
			console.log(data);
			var resp = JSON.parse(data);
			if (resp != "ok") {
				alert('Error: ' + resp);
			} else
				$('#status').text('User password changed succesfully');
		});

	}
};
