var addreminder = {
	init: function() {
		$('#dlg_addreminder').dialog({
			autoOpen: false,
			position: { my:'bottom', at:'top-20px', of:'#btn_addcomment' },
			modal: true,
			title: 'Add Reminder'
		});

		$('#reminder_on').datepicker({ dateFormat:'M d, yy' });

		$('#reminder_save').click(addreminder.save);
	},

	show: function(case_id) {
		$('#dlg_addreminder').data('case_id', case_id).dialog('open');
		addreminder.reset();
	},

	save: function() {
		var err;
		if (err = addreminder.validate()) {
			alert(err);
			return;
		}

		var param = {};
		param.case_id = $('#dlg_addreminder').data('case_id');
		param.on = $('#reminder_on').val();
		param.comment = $('#reminder_comment').val();

		$('.ajaxstatus').text('Adding reminder...').show();
		$.post('/case/save_reminder.php', param, function(data){
			//console.log('addreminder.save recv:' + data);
			var resp = JSON.parse(data);
			if (resp != "ok") {
				alert('Error:' + resp);
				return;
			}

			$('.ajaxstatus').text('Added reminder').fadeOut();
			$('#dlg_addreminder').dialog('close');

			navigation.update_case_stats();

			details.show(param.case_id, false);
		});
	},

	reset: function() {
		$('#reminder_on, #reminder_comment').val('');
	},

	validate: function() {
		if ($('#reminder_on').val() == '')
			return 'Invalid date specified';
		return null;
	}
};
