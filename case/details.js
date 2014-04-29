var details = {
	init: function() {
		$('#dlg_editcase').dialog({
			autoOpen: false, 
			position: { my:'top', at:'bottom', of:'.header'} ,
			width: 500,
			dialogClass: 'dialog',
			title: 'Add/Edit Case Details',
			show: true,
			modal: true,
			open: details.dlg_editcase_open
		});

		$('#dlg_editproceeding').dialog({
			autoOpen: false, 
			position: { my:'bottom', at:'top', of:'#addproceedings'} ,
			width: 500,
			dialogClass: 'dialog',
			title: 'Add/Edit Case Proceedings',
			show: true,
			modal: true,
			open: details.dlg_editproceeding_open
		});


		$('#btn_update').click(function(){
			$('#dlg_editcase').dialog('open');
		});

		$('#btn_addproceeding').click(function(){
			$('#dlg_editproceeding').dialog('open');
		});
	},

	dlg_editcase_open: function() {
		$('#dlg_editcase').load('/case/editcase.php', function(){
			//alert('loaded');
		});
	},

	dlg_editproceeding_open: function() {
		$('#dlg_editproceeding').load('/case/editproceeding.php', function(){
			//alert('loaded');
			$('#dlg_editproceeding').dialog('option', 'position', { my:'bottom', at:'top', of:'#addproceedings' });
		});
	}
};
