var addproceeding = {
	init: function() {
		$('#dlg_addproceeding').dialog({
			autoOpen: false,
			position: { my:'bottom', at:'top-20px', of:'#btn_addcomment' },
			modal: true,
			title: 'Add Proceeding',
			width:500
		});

		$('#btn_save_proceeding').click(addproceeding.save);

		$('#proc_hearing').datepicker({ dateFormat:'M d, yy' });

		utils.dynamic_combo('#proc_court', '/common/get_courts.php', null);
	},

	show: function(case_id) {
		$('#dlg_addproceeding').data('case_id', case_id).dialog('open');
		addproceeding.reset();
	},

	save: function() {

		var err;
		if (err = addproceeding.validate()) {
			alert(err);
			return;
		}

		var case_id = $('#dlg_addproceeding').data('case_id');
		var param = {};
		param.case_id = case_id;
		param.court = $('#proc_court').val();
		param.hall = $('#proc_hall').val();
		param.item = $('#proc_item').val();
		param.judge = $('#proc_judge').val();
		param.counsel = $('#proc_counsel').val();
		param.disposal = $('#proc_disposal').val();
		param.hearing = $('#proc_hearing').val();
		param.comment = $('#proc_remarks').val();

		$('.ajaxstatus').text('Adding proceeding...').show();
		$.post('/case/save_proceeding.php', param, function(data){
			var resp = JSON.parse(data);
			if (resp != "ok") {
				alert('Error:' + resp);
				$('.ajaxstatus').hide();
				return;
			}

			$('.ajaxstatus').text('Proceeding added').fadeOut();
			$('#dlg_addproceeding').dialog('close');
			navigation.update_case_stats();
			details.show(case_id, false);
		});
	},

	reset: function() {
		$('#proc_court, #proc_disposal, #proc_disposal').val('1');
		$('#proc_hall, #proc_item, #proc_judge, #proc_hearing, #proc_remarks').val('');
	},

	validate: function() {
		return null;
	}
};
