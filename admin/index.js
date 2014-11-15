
var app = {
	init: function() {

		$('div#user_edit').dialog({
			autoOpen:false,
			modal: true,
			open: app.user_edit_dlg_on_open,
			title: 'Add/Edit User Information',
			position: { my:'top', at:'top', of:'#listofusers'},
			width: 400,
			height:500
		});

		utils.dynamic_combo('#grade', '/common/get_grades.php', null);

		$('#to').autocomplete({
			source: '/common/get_user_autocomplete.php',
			select: function(event,ui) {
				$('#to').val(ui.item.label).data('id', ui.item.value);
				return false;
			}
		});

		$('button#user_add').click(function(){
			$('div#user_edit').data('id',null).dialog('open');
		});

		$('button#save').click(function(){
			app.user_save();
		});

		$('button#reset_password').click(function(){
			app.user_reset_password();
		});

		$('button#move_cases').click(function(){
			app.move_cases();
		});

		$('button#remove_user').click(function(){
			app.user_remove();
		});

		app.user_list_update();

	},

	user_list_update: function() {
		$.get('/admin/user_list.php', null, function(data) {
			//console.log('user_list_update recv:' + data);
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
		if ($('#user_edit').data('id')) {
			param.action = 'update';
			param.id = $('#user_edit').data('id');
		} else
			param.action = 'new';
		param.login = $('#login').val();
		param.name = $('#name').val();
		param.grade = $('#grade').val();
		$.post('/admin/user_save.php', param, function(data){
			//console.log('user_save recv:' + data);
			var resp = JSON.parse(data);
			if (resp != 'ok') {
				alert('Error:' + data);
				return;
			}
			$('div#user_edit').dialog('close');
			app.user_list_update();
		});
	},

	user_remove: function() {
		var param = {};
		param.action = 'remove';
		param.id = $('#user_edit').data('id');
		$.post('/admin/user_remove.php', param, function(data){
			//console.log('user_save recv:' + data);
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
		param.id = $('#user_edit').data('id');
		$.post('/admin/user_save.php', param, function(data){
			//console.log('user_reset_password recv:' + data);
			var resp = JSON.parse(data);
			if (resp != 'ok') {
				alert('Error:' + data);
				return;
			}
			$('div#user_edit').dialog('close');
		});

	},

	move_cases: function() {
		if (!$('#to').data('id')) {
			alert('Invalid officer specified');
			return;
		}

		var param = {};
		param.from = $('#user_edit').data('id');
		param.to = $('#to').data('id');
		$.post('/admin/move_cases.php', param, function(data){
			console.log('move_cases recv:' + data);
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
				//console.log('user_edit_dlg_on_open recv:' + data);
				var resp = JSON.parse(data);
				$('#login').val(resp.login);
				$('#name').val(resp.name);
				$('#grade').val(resp.grade);
			});
		}
	}
};
