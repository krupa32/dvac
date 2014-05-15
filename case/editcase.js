var editcase = {
	init: function() {
		$('#sel_category').change(function(){
			$('#txt_case_num').val(this.options[this.selectedIndex].text);
		});
		
		$('#txt_investigator').autocomplete({
			source: '/common/get_user_autocomplete.php',
			select: function(event,ui) {
				$('#txt_investigator').val(ui.item.label).data('id', ui.item.value);
				return false;
			}
		});

		$('#btn_save_case').click(editcase.save);

	},

	show: function(id) {
		$('.page').hide();
		$('#page_editcase').data('id', id).show();

		$('#sel_category').attr('disabled', false);

		// get case categories
		utils.dynamic_combo('#sel_category', '/common/get_categories.php', null);

		if (!id)
			return;

		// get and fill in case details corresponding to id
		var param = {};
		param.id = id;
		$.get('/case/get_details.php', param, function(data){
			//console.log('editcase.show recv:' + data);
			var resp = JSON.parse(data);
			$('#txt_case_num').val(resp.case_num);
			$('#txt_investigator').val(resp.investigator).data('id', resp.investigator_id);
			$('#ta_petitioner').val(resp.petitioner);
			$('#ta_respondent').val(resp.respondent);
			$('#ta_prayer').val(resp.prayer);

			$('#sel_category').attr('disabled', true);
		});

	},

	save: function() {
		var param = {};
		param.id = $('#page_editcase').data('id');
		param.case_num = $('#txt_case_num').val();
		param.investigator = $('#txt_investigator').data('id');
		param.petitioner = $('#ta_petitioner').val();
		param.respondent = $('#ta_respondent').val();
		param.prayer = $('#ta_prayer').val();
		$.post('/case/save_case.php', param, function(data){
			//console.log('save_case recv:' + data);
			var resp = JSON.parse(data);
			if (typeof resp === 'string') {
				alert('Error:' + resp);
				return;
			}

			navigation.update_case_stats();

			// open the case details with the new case id
			details.show(resp);
		});
	}
};
