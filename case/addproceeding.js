var addproceeding = {
	init: function() {
		$('#dlg_addproceeding').dialog({
			autoOpen: false,
			position: { my:'bottom', at:'top-20px', of:'#btn_addcomment' },
			modal: true,
			title: 'Add Proceeding'
		});

		$('#btn_save_proceeding').click(addproceeding.save);
	},

	show: function(case_id) {
		$('#dlg_addproceeding').data('case_id', case_id).dialog('open');
	},

	save: function() {
		var case_id = $('#dlg_addproceeding').data('case_id');
		var param = {};
		param.case_id = case_id;
		param.court = $('#proc_court').val();
		param.hall = $('#proc_hall').val();
		param.item = $('#proc_item').val();
		param.judge = $('#proc_judge').val();
		param.counsel = $('#proc_counsel').val();
		param.disposal = $('#proc_disposal').val();
		param.comment = $('#proc_remarks').val();
		$.post('/case/save_proceeding.php', param, function(data){
			var resp = JSON.parse(data);
			if (resp != "ok") {
				alert('Error:' + resp);
				return;
			}
			$('#dlg_addproceeding').dialog('close');
			details.show(case_id);
		});
	}
};
