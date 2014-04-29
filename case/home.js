var home = {
	init: function() {
		$('#dlg_editcase').dialog({
			autoOpen: false, 
			position: { my:'top', at:'bottom', of:'.header'} ,
			width: 500,
			dialogClass: 'dialog',
			title: 'Add/Edit Case Details',
			show: true,
			modal: true,
			open: home.dlg_editcase_open
		});

		$('#btn_addcase').click(function(){
			$('#dlg_editcase').data({ id:null }).dialog('open');
		});
	},

	dlg_editcase_open: function() {
		$('#dlg_editcase').load('/case/editcase.php', function(){
			//alert('loaded');
		});
	}
};
