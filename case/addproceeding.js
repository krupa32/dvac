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

		//$('#proc_hearing').datepicker({ changeYear:true, dateFormat:'M d, yy' });
		$('#proc_hearing').datepicker({ changeYear:true, dateFormat:'yy' });
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
		var param = new FormData();
		param.append('case_id', case_id);
		param.append('court', $('#proc_court').val());
		param.append('hall', $('#proc_hall').val());
		param.append('item', $('#proc_item').val());
		param.append('judge', $('#proc_judge').val());
		param.append('counsel', $('#proc_counsel').val());
		param.append('disposal', $('#proc_disposal').val());
		param.append('hearing', $('#proc_hearing').val());
		param.append('comment', $('#proc_remarks').val());
		$.ajax({
			url: '/case/save_proceeding.php',
			type: 'POST',
			data: param,
			processData: false,
			contentType: false,
			success: function(data){
				var resp = JSON.parse(data);
				if (resp != "ok") {
					alert('Error:' + resp);
					return;
				}
				$('#dlg_addproceeding').dialog('close');
				navigation.update_case_stats();
				details.show(case_id, false);
			}
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
