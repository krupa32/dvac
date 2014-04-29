
var app = {
	init: function() {

		$('div#user_edit').dialog({
			autoOpen:false,
			modal: true,
			open: app.user_edit_dlg_on_open,
			title: 'Add/Edit User Information',
			position: { my:'top', at:'top', of:'#listofusers'}
		});

		app.user_list_update();

		$('button#user_add').click(function(){
			$('div#user_edit').data('id',null).dialog('open');
		});

		$('button#user_save').click(function(){
			app.user_save();
		});

		$('button#user_reset_password').click(function(){
			app.user_reset_password();
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
				$('div#user_edit').data('id',$(this).attr('id')).dialog('open');
				return false;
			});
		});

	},

	user_save: function() {
		var param = {};
		if ($('#user_edit').data('id'))
			param.action = 'update';
		else
			param.action = 'new';
		param.id = $('#user_id').val();
		param.name = $('#user_name').val();
		param.grade = $('#user_grade').val();
		$.post('/admin/user_save.php', param, function(data){
			var resp = JSON.parse(data);
			if (resp != 'ok') {
				alert('Error:' + data);
				return;
			}
			$('div#user_edit').dialog('close');
			app.user_list_update();
		});
	},

	user_reset_password: function() {
		var param = {};
		param.action = 'reset';
		param.id = $('#user_id').val();
		$.post('/admin/user_save.php', param, function(data){
			var resp = JSON.parse(data);
			if (resp != 'ok') {
				alert('Error:' + data);
				return;
			}
			$('div#user_edit').dialog('close');
		});

	},

	user_edit_dlg_on_open: function() {
		var id = $('#user_edit').data('id');
		if (id) {
			var param = {};
			param.id = id;
			$.get('/admin/user_get.php', param, function(data){
				var resp = JSON.parse(data);
				$('#user_id').val(resp.id);
				$('#user_name').val(resp.name);
				$('#user_grade').val(resp.grade);
			});
		}
	}
};
