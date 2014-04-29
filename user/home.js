var home = {
	init: function() {
		$.get('/user/get_details.php', null, function(data){
			var resp = JSON.parse(data);
			$('#user_name').val(resp.name);
			$('#user_grade').val(resp.grade);
			$('#user_rep_officer').val(resp.reporting_officer);
			$('#user_rep_officer').data('id', resp.reporting_officer_id);
			$('#user_location').val(resp.location);
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
