var editcase = {
	init: function() {
		// get case categories and courts
		utils.dynamic_combo('#editcase_category', '/common/get_categories.php', null);
		utils.dynamic_combo('#editcase_court', '/common/get_courts.php', null);

		$('#editcase_category').change(editcase.update_case_num);
		$('#editcase_court').change(editcase.update_case_num);
		$('#editcase_no, #editcase_year').keyup(editcase.update_case_num);

		$('#editcase_no').focus(function(){
			if ($(this).val() == 'Number')
				$(this).val('');
		}).blur(function(){
			if ($(this).val() == '')
				$(this).val('Number');
		});
		
		$('#editcase_year').focus(function(){
			if ($(this).val() == 'Year')
				$(this).val('');
		}).blur(function(){
			if ($(this).val() == '')
				$(this).val('Year');
		});
		
		$('#editcase_investigator').autocomplete({
			source: '/common/get_user_autocomplete.php',
			select: function(event,ui) {
				$('#editcase_investigator').val(ui.item.label).data('id', ui.item.value);
				return false;
			}
		});

		$('#editcase_next_hearing').datepicker({ dateFormat:'M d, yy' });

		$('#editcase_save').click(editcase.save);

	},

	update_case_num: function() {
		var court_spec, case_num;
		court_spec = ($('#editcase_court option:selected').text() == 'Madurai') ? '(MD)' : '';
		case_num = $('#editcase_category option:selected').text() + '.' + court_spec +
				 $('#editcase_no').val().trim() + '/' + $('#editcase_year').val().trim();
		 $('#editcase_case_num').text(case_num);
	},

	show: function(id, push) {
		$('.page').hide();
		$('#page_editcase').data('id', id).show();
		editcase.reset();

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
			$('#editcase_case_num').text(resp.case_num);
			$('#editcase_investigator').val(resp.investigator).data('id', resp.investigator_id);
			$('#editcase_petitioner').val(resp.petitioner);
			$('#editcase_respondent').val(resp.respondent);
			$('#editcase_prayer').val(resp.prayer);

			if (resp.next_hearing != 'None')
				$('#editcase_next_hearing').val(resp.next_hearing);

			if (resp.case_num.indexOf('(MD)') != -1)
				$('#editcase_court').val(2);

			//  parse the case number and year from case_num
			var num_index = resp.case_num.lastIndexOf(".") + 1;
			if (resp.case_num.charAt(num_index) == '(') // if court other than chennai, skip court spec
				num_index += 4;
			var year_index = resp.case_num.lastIndexOf("/") + 1;
			var num = resp.case_num.substring(num_index, year_index - 1)
			var year = resp.case_num.substr(year_index)
			$('#editcase_no').val(num);
			$('#editcase_year').val(year);
		});

	},

	save: function() {

		var err;
		if (err = editcase.validate()) {
			alert(err);
			return;
		}

		var param = {};
		param.id = $('#page_editcase').data('id');
		param.case_num = $('#editcase_case_num').text();
		param.category = $('#editcase_category').val();
		param.investigator = $('#editcase_investigator').data('id');
		param.petitioner = $('#editcase_petitioner').val();
		param.respondent = $('#editcase_respondent').val();
		param.prayer = $('#editcase_prayer').val();
		param.next_hearing = $('#editcase_next_hearing').val();

		$('.ajaxstatus').text('Saving case...').show();
		$.post('/case/save_case.php', param, function(data) {
			//console.log('save_case recv:' + data);
			var resp = JSON.parse(data);
			if (typeof resp === 'string') {
				alert('Error:' + resp);
				$('.ajaxstatus').hide();
				return;
			}

			$('.ajaxstatus').text('Case saved').fadeOut();
			navigation.update_case_stats();

			// open the case details with the new case id
			details.show(resp, true);
		});
	},

	reset: function() {
		$('#editcase_category').val('1').get(0).selectedIndex = 0;
		$('#editcase_court').val('1').get(0).selectedIndex = 0;
		$('#editcase_no').val('Number');
		$('#editcase_year').val('Year');
		editcase.update_case_num();
		$('#editcase_investigator').val('').data('id', null);
		$('#editcase_petitioner, #editcase_respondent, #editcase_prayer').val('');
		$('#editcase_next_hearing').val('');
	},

	validate: function() {
		var year = $('#editcase_year');
		var no = $('#editcase_no');

		if (no.val() == 'Number' || isNaN(parseInt(no.val())))
			return 'Invalid number specified for case';
		if (year.val() == 'Year' || year.val().length != 4)
			return 'Invalid year. Enter a 4-digit year';
		if (!$('#editcase_investigator').data('id'))
			return 'Invalid Investigator specified';
		return null;
	}
};
