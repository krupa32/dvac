var toolbar = {
	init: function() {

		$('#toolbar_addcase').click(function(){
			editcase.show(null, true);
		});

		var hint = 'Case number, petitioner or respondent';
		$('#toolbar_data').focus(function(){
			if ($(this).val() == hint)
				$(this).val('').removeClass('searchhint').addClass('normal');
		}).blur(function(){
			if ($(this).val() == '')
				$(this).val(hint).removeClass('normal').addClass('searchhint');
		});

		$('#toolbar_data').blur();

		$('#toolbar_data').keydown(function(e){
			if (e.which == 13) { // enter key
				caselist.show({ type:'search', value:$(this).val() }, false, true);
			}
		});

		$('#toolbar_advanced').click(function(e){
			advancedsearch.show(true);
			e.preventDefault();
		});

		if (!(user_caps & CAP_ADDCASE))
			$('#toolbar_addcase').attr('disabled', true);

	}
};
