var toolbar = {
	init: function() {
		/* set autocomplete for input field, initially disabled */
		$('#toolbar_data').autocomplete({
			source: '/common/get_user_autocomplete.php',
			disabled: true,
			select: function(event,ui) {
				$('#toolbar_data').val(ui.item.label).data('id', ui.item.value);
				return false;
			}
		});

		$('#btn_addcase').click(function(){
			editcase.show(null);
		});

		$('#toolbar_category').change(function(){
			var cur = $(this).val();
			if (cur == 'investigator' || cur == 'assigned_to')
				$('#toolbar_data').autocomplete('enable');
			else
				$('#toolbar_data').autocomplete('disable');
		});

		$('#btn_search').click(function(){
			search.show('search');
		});
	}
};
