var recent = {
	init: function() {
	},

	show: function() {
		$('.page').hide();
		$('#page_recent a').click(function(){
			details.show();
			return false;
		});
		$('#page_recent').show();
	}
};
