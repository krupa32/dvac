var changestatus = {
	init: function() {
		$('#dlg_changestatus').dialog({
			autoOpen: false,
			position: { my:'left top', at:'left bottom', of:'#details_status' },
			modal: true,
			title: 'Change Case Status'
		});

		utils.dynamic_combo('#changestatus_status', '/common/get_statuses.php', null);

		$('#changestatus_save').click(changestatus.save);
	},

	show: function(id) {
		$('#dlg_changestatus').data('id', id).dialog('open');
	},

	save: function() {
		var case_id = $('#dlg_changestatus').data('id');
		var param = {};
		param.case_id = case_id;
		param.status = $('#changestatus_status').val();

		$('.ajaxstatus').text('Changing status...').show();
		$.post('/case/save_status.php', param, function(data){
			var resp = JSON.parse(data);
			if (resp != "ok") {
				alert('Error:' + resp);
				$('.ajaxstatus').hide();
				return;
			}
			
			$('.ajaxstatus').text('Status changed').fadeOut();
			$('#dlg_changestatus').dialog('close');
			navigation.update_case_stats();

			details.show(case_id, false);
		});
	}

};
