var editcase = {
	init: function() {
		// get case categories
		utils.dynamic_combo('#editcase_category', '/common/get_categories.php', null);

		$('#editcase_category').change(function(){
			$('#editcase_case_num').val(this.options[this.selectedIndex].text);
		});
		
		$('#editcase_investigator').autocomplete({
			source: '/common/get_user_autocomplete.php',
			select: function(event,ui) {
				$('#editcase_investigator').val(ui.item.label).data('id', ui.item.value);
				return false;
			}
		});

		$('#editcase_save').click(editcase.save);

	},

	show: function(id, push) {
		$('.page').hide();
		$('#page_editcase').data('id', id).show();
		if (push)
			history.pushState({ page:'editcase', id:id }, '', '#editcase/' + id);

		if (!id) // new case, nothing more to do
			return;

		// get and fill in case details corresponding to id
		var param = {};
		param.id = id;
		$.get('/case/get_details.php', param, function(data) {
			//console.log('editcase.show recv:' + data);
			var resp = JSON.parse(data);
			$('#editcase_category').val(resp.category);
			$('#editcase_case_num').val(resp.case_num);
			$('#editcase_investigator').val(resp.investigator).data('id', resp.investigator_id);
			$('#editcase_petitioner').val(resp.petitioner);
			$('#editcase_respondent').val(resp.respondent);
			$('#editcase_prayer').val(resp.prayer);
		});

	},

	save: function() {
		var param = {};
		param.id = $('#page_editcase').data('id');
		param.case_num = $('#editcase_case_num').val();
		param.category = $('#editcase_category').val();
		param.investigator = $('#editcase_investigator').data('id');
		param.petitioner = $('#editcase_petitioner').val();
		param.respondent = $('#editcase_respondent').val();
		param.prayer = $('#editcase_prayer').val();
		$.post('/case/save_case.php', param, function(data) {
			//console.log('save_case recv:' + data);
			var resp = JSON.parse(data);
			if (typeof resp === 'string') {
				alert('Error:' + resp);
				return;
			}

			// open the case details with the new case id
			details.show(resp, true);
		});
	}
};
