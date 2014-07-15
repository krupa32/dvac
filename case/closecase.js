var closecase = {
	init: function() {
		$('#dlg_closecase').dialog({
			autoOpen: false,
			position: { my:'right bottom', at:'right top-20px', of:'#details_close' },
			modal: true,
			title: 'Close Case'
		});

		$('#btn_close_case').click(closecase.save);
	},

	show: function(case_id) {
		$('#dlg_closecase').data('case_id', case_id).dialog('open');
	},

	save: function() {
		var case_id = $('#dlg_closecase').data('case_id');
		var param = {};
		param.case_id = case_id;
		param.comment = $('#close_text').val();

		$('.ajaxstatus').text('Closing case...').show();
		$.post('/case/close_case.php', param, function(data){
			//console.log('closecase.save recv:' + data);
			var resp = JSON.parse(data);
			if (resp != "ok") {
				alert('Error:' + resp);
				$('.ajaxstatus').hide();
				return;
			}

			$('.ajaxstatus').text('Case closed').fadeOut();
			$('#dlg_closecase').dialog('close');
			navigation.update_case_stats();
			details.show(case_id);
		});
	}
};
