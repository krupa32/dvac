var assign = {
	init: function() {
		$('#dlg_assign').dialog({
			autoOpen: false,
			position: { my:'bottom', at:'top-20px', of:'#btn_addcomment' },
			modal: true,
			title: 'Assign Case'
		});

		$('#assign_to').autocomplete({
			source: '/common/get_user_autocomplete.php',
			select: function(event,ui) {
				$('#assign_to').val(ui.item.label).data('id', ui.item.value);
				return false;
			}
		});

		$('#btn_save_assignment').click(assign.save);
	},

	show: function(case_id) {
		$('#dlg_assign').data('case_id', case_id).dialog('open');
		assign.reset();
	},

	save: function() {
		var err;
		if (err = assign.validate()) {
			alert(err);
			return;
		}

		var param = {};
		param.case_id = $('#dlg_assign').data('case_id');
		param.target = $('#assign_to').data('id');
		param.comment = $('#assign_comment').val();

		$('.ajaxstatus').text('Assigning case...').show();
		$.post('/case/save_assignment.php', param, function(data){
			//console.log('assign.save recv:' + data);
			var resp = JSON.parse(data);
			if (resp != "ok") {
				alert('Error:' + resp);
				$('.ajaxstatus').hide();
				return;
			}

			$('.ajaxstatus').text('Case assigned').fadeOut();
			$('#dlg_assign').dialog('close');

			navigation.update_case_stats();

			details.show(param.case_id, false);
		});
	},

	reset: function() {
		$('#assign_to').val('').data('id', null);
	},

	validate: function() {
		if (!$('#assign_to').data('id'))
			return 'Invalid officer specified';
		return null;
	}
};
