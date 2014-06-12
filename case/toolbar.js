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

		$('#toolbar_addcase').click(function(){
			editcase.show(null, true);
		});

		$('#toolbar_field').change(function(){
			var cur = $(this).val();
			if (cur == 'investigator' || cur == 'assigned_to') {
				$('#toolbar_data').autocomplete({
					source: '/common/get_user_autocomplete.php' 
				}).autocomplete('enable');
			} else if (cur == 'location') {
				$('#toolbar_data').autocomplete({
					source: '/common/get_location_autocomplete.php' 
				}).autocomplete('enable');
			} else {
				$('#toolbar_data').autocomplete('disable');
			}
		});

		$('#toolbar_search').click(function(){
			var f = $('#toolbar_field').val();
			var d = $('#toolbar_data').val();

			console.log('search called');
			/* special case where user id is queried */
			if (f == 'investigator' || f == 'assigned_to' || f == 'location')
				d = $('#toolbar_data').data('id');

			var arg = { type:'search', field:f, data:d };
			caselist.show(arg, false, true);
		});
	}
};
