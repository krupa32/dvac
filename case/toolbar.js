var toolbar = {
	init: function() {

		$('#toolbar_addcase').click(function(){
			editcase.show(null, true);
		});

		$('#toolbar_search').click(function(){
			var f = $('#toolbar_field').val();
			var d = $('#toolbar_data').val();

			console.log('search called');
			/* special case where user id is queried */
			if (f == 'investigator' || f == 'assigned_to') // || f == 'location')
				d = $('#toolbar_data').data('id');

			var arg = { type:'search', field:f, data:d };
			//caselist.show(arg, false, true);
		});
		
		var hint = 'Enter case number, petitioner or respondent';
		$('#toolbar_data').focus(function(){
			if ($(this).val() == hint)
				$(this).val('').removeClass('searchhint').addClass('normal');
		}).blur(function(){
			if ($(this).val() == '')
				$(this).val(hint).removeClass('normal').addClass('searchhint');
		});

		$('#toolbar_data').focus();
	}
};
