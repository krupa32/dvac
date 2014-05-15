var addcomment = {
	init: function() {
		$('#dlg_addcomment').dialog({
			autoOpen: false,
			position: { my:'bottom', at:'top-20px', of:'#btn_addcomment' },
			modal: true,
			title: 'Add Comment'
		});

		$('#btn_save_comment').click(addcomment.save);
	},

	show: function(case_id) {
		$('#dlg_addcomment').data('case_id', case_id).dialog('open');
	},

	save: function() {
		var case_id = $('#dlg_addcomment').data('case_id');
		var param = {};
		param.case_id = case_id;
		param.comment = $('#comment_text').val();
		$.post('/case/save_comment.php', param, function(data){
			//console.log('addcomment.save recv:' + data);
			var resp = JSON.parse(data);
			if (resp != "ok") {
				alert('Error:' + resp);
				return;
			}
			$('#dlg_addcomment').dialog('close');
			details.show(case_id);
		});
	}
};
