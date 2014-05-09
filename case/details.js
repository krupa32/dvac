var details = {
	init: function() {
		$('#btn_addproceeding').click(function(){
			addproceeding.show();
		});
		$('#btn_addcomment').click(function(){
			addcomment.show();
		});
		$('#btn_assign').click(function(){
			assign.show();
		});
	},

	show: function() {
		$('.page').hide();
		$('#page_details').show();
	}
};
