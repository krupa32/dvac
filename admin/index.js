
var app = {
	init: function() {

		app.user_list_update();

		$('button#user_add').click(function(){
			$('div#user_edit').dialog();
		});

		$('button#user_save').click(function(){
			app.user_save('new');
		});
	},

	user_list_update: function() {
		$.get('/admin/user_list.php', null, function(data) {
			var resp = JSON.parse(data);
			var div = $('div#users');
			div.html('');
			for (i in resp) {
				div.append('<p><a class="user" id="' + resp[i].id + '" href="">' + resp[i].name + '</a></p>');
			}

			$('a.user').click(function() {
				//alert('user edit:' + $(this).attr('id'));
				$('div#user_edit').dialog();
				return false;
			});
		});

	},

	user_save: function(action) {
		var param = {};
		param.action = action;
		param.id = $('#user_id').val();
		param.name = $('#user_name').val();
		param.level = $('#user_level').val();
		param.password = $('#user_password').val();
		param.rep_id = $('#user_rep_id').val();
		$.post('/admin/user_save.php', param, function(data){
			var resp = JSON.parse(data);
			if (resp != 'ok') {
				alert('Error:' + data);
				return;
			}
			$('div#user_edit').dialog('close');
			app.user_list_update();
		});
	}
};
