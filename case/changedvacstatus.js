var changedvacstatus = {
	init: function() {
		$('#dlg_changedvacstatus').dialog({
			autoOpen: false,
			position: { my:'left top', at:'left bottom', of:'#details_dvac_status' },
			modal: true,
			title: 'Change DVAC Status'
		});

		utils.dynamic_combo('#changedvacstatus_status', '/common/get_dvac_statuses.php', null);
		utils.dynamic_combo('#changedvacstatus_direction', '/common/get_directions.php', null);

		$('#changedvacstatus_status').change(changedvacstatus.change);

		$('#changedvacstatus_save').click(changedvacstatus.save);
	},

	show: function(id) {
		$('#dlg_changedvacstatus').data('id', id).dialog('open');
	},

	change: function() {
		if ($('#changedvacstatus_status option:selected').text() == "DVAC_OPEN")
			$('#changedvacstatus_direction').attr('disabled', false);
		else
			$('#changedvacstatus_direction').attr('disabled', true);
	},

	save: function() {
		var case_id = $('#dlg_changedvacstatus').data('id');
		var param = {};
		param.case_id = case_id;
		param.dvac_status = $('#changedvacstatus_status').val();
		param.direction = $('#changedvacstatus_direction').val();

		$('.ajaxstatus').text('Changing status...').show();
		$.post('/case/save_dvac_status.php', param, function(data){
			var resp = JSON.parse(data);
			if (resp != "ok") {
				alert('Error:' + resp);
				$('.ajaxstatus').hide();
				return;
			}
			
			$('.ajaxstatus').text('Status changed').fadeOut();
			$('#dlg_changedvacstatus').dialog('close');
			navigation.update_case_stats();

			details.show(case_id, false);
		});
	}

};
