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
		addcomment.reset();
	},

	save: function() {
		var err;
		if (err = addcomment.validate()) {
			alert(err);
			return;
		}

		var case_id = $('#dlg_addcomment').data('case_id');
		var param = {};
		param.case_id = case_id;
		param.comment = $('#comment_text').val();

		$('.ajaxstatus').text('Saving comment...').show();
		$.post('/case/save_comment.php', param, function(data){
			//console.log('addcomment.save recv:' + data);
			var resp = JSON.parse(data);
			if (resp != "ok") {
				alert('Error:' + resp);
				$('.ajaxstatus').hide();
				return;
			}

			$('.ajaxstatus').text('Comment saved').fadeOut(1000);
			$('#dlg_addcomment').dialog('close');
			details.show(case_id, false);
		});
	},

	reset: function() {
		$('#comment_text').val('');
	},

	validate: function() {
		return null;
	}
};
