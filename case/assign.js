var assign = {
	init: function() {
		$('#dlg_assign').dialog({
			autoOpen: false,
			position: { my:'bottom', at:'top-20px', of:'#btn_addcomment' },
			modal: true,
			title: 'Assign Case'
		});
	},

	show: function() {
		$('#dlg_assign').dialog('open');
	}
};
