var toolbar = {
	init: function() {
		$('#btn_addcase').click(function(){
			editcase.show(null);
		});
		$('#btn_search').click(function(){
			search.show();
		});
	}
};
