var addcomment = {
	init: function() {
		$('#dlg_addcomment').dialog({
			autoOpen: false,
			position: { my:'bottom', at:'top-20px', of:'#btn_addcomment' },
			modal: true,
			title: 'Add Comment'
		});
	},

	show: function() {
		$('#dlg_addcomment').dialog('open');
	}
};
