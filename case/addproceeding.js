var addproceeding = {
	init: function() {
		$('#dlg_addproceeding').dialog({
			autoOpen: false,
			position: { my:'bottom', at:'top-20px', of:'#btn_addcomment' },
			modal: true,
			title: 'Add Proceeding'
		});
	},

	show: function() {
		$('#dlg_addproceeding').dialog('open');
	}
};
