var advancedsearch = {
	init: function() {
		// init detachments
		$.get('/common/get_locations.php', null, function(data){
			var resp = JSON.parse(data), i;
			var div = $('#filter_detachment');
			for (i in resp)
				div.append('<label><input type="checkbox" value="' + resp[i].value + '"></input> ' + resp[i].name + '</label>');
		});

		// init categories
		$.get('/common/get_categories.php', null, function(data){
			var resp = JSON.parse(data), i;
			var div = $('#filter_category');
			for (i in resp)
				div.append('<label><input type="checkbox" value="' + resp[i].value + '"></input> ' + resp[i].name + '</label>');
		});

		// init courts
		$.get('/common/get_courts.php', null, function(data){
			var resp = JSON.parse(data), i;
			var div = $('#filter_court');
			for (i in resp)
				div.append('<label><input type="checkbox" value="' + resp[i].value + '"></input> ' + resp[i].name + '</label>');
		});

		$('#advanced_investigator').data('id', null);
		$('#advanced_investigator').autocomplete({
			source: '/common/get_user_autocomplete.php',
			select: function(event,ui) {
				$('#advanced_investigator').val(ui.item.label).data('id', ui.item.value);
				return false;
			}
		});

		$('#advanced_assignedto').data('id', null);
		$('#advanced_assignedto').autocomplete({
			source: '/common/get_user_autocomplete.php',
			select: function(event,ui) {
				$('#advanced_assignedto').val(ui.item.label).data('id', ui.item.value);
				return false;
			}
		});

		$('#advanced_investigator, #advanced_assignedto').change(function(){
			var e = $(this);
			if (e.val() == "")
				e.data('id', null);
		});

		$('#advanced_hearingafter').datepicker({ dateFormat:'M d, yy' });
		$('#advanced_hearingbefore').datepicker({ dateFormat:'M d, yy' });

		$('#advanced_search').click(advancedsearch.search);
		$('#advanced_reset').click(advancedsearch.reset);

	},

	show: function(push) {
		$('.page').hide();
		$('#page_advancedsearch').show();

		if (push)
			history.pushState({ page:'advancedsearch' }, '', '#advancedsearch');

	},

	search: function() {
		var param = {}, count;

		param.status = {};
		count = 0;
		$('#filter_status input').each(function(){
			if (this.checked)
				param.status[count++] = $(this).val();
		});

		param.location = {};
		count = 0;
		$('#filter_detachment input').each(function(){
			if (this.checked)
				param.location[count++] = $(this).val();
		});

		param.category = {};
		count = 0;
		$('#filter_category input').each(function(){
			if (this.checked)
				param.category[count++] = $(this).val();
		});

		param.court = {};
		count = 0;
		$('#filter_court input').each(function(){
			if (this.checked)
				param.court[count++] = $(this).val();
		});

		param.investigator = $('#advanced_investigator').data('id');
		param.assigned_to = $('#advanced_assignedto').data('id');
		param.hearingafter = $('#advanced_hearingafter').val();
		param.hearingbefore = $('#advanced_hearingbefore').val();
		param.tag = $('#advanced_tag').val();

		console.log('param:' + JSON.stringify(param));

		var arg = { type:'advanced', param:param };
		caselist.show(arg, false, true);
	},

	reset: function() {
		$('#filter_status input').each(function(){
			this.checked = false;
		});
		$('#filter_detachment input').each(function(){
			this.checked = false;
		});
		$('#filter_category input').each(function(){
			this.checked = false;
		});
		$('#advanced_investigator').val('').data('id', null);
		$('#advanced_assignedto').val('').data('id', null);
		$('#advanced_hearingafter').val('');
		$('#advanced_hearingbefore').val('');
		$('#advanced_tag').val('');
	}
};
