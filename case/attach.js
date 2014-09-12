var attach = {
	init: function() {
		$('#dlg_attach').dialog({
			autoOpen: false,
			position: { my:'bottom', at:'top-20px', of:'#btn_addcomment' },
			modal: true,
			title: 'Attach a file'
		});

		utils.dynamic_combo('#attach_type', '/common/get_attachment_types.php', null);

		$('#attach_save').click(attach.save);
	},

	show: function(case_id) {
		$('#dlg_attach').data('case_id', case_id).dialog('open');
		attach.reset();
	},

	save: function() {
		var file = $('#attach_file').get(0).files[0];
		var fd = new FormData();
		fd.append('attachment', file);
		fd.append('case_id', $('#dlg_attach').data('case_id'));
		fd.append('type', $('#attach_type').val());
		fd.append('comment', $('#attach_comment').val());

		$('.ajaxstatus').text('Uploading...').show();
		$.ajax({
			url: '/case/save_attachment.php',
			type: 'POST',
			data: fd,
			processData: false,
			contentType: false,
			success: function(data) {
				//console.log('save_attachment recv:' + data);
				var resp = JSON.parse(data);
				if (resp != 'ok') {
					alert('Error:' + resp);
					return;
				}
				$('.ajaxstatus').text('Upload successful').fadeOut();
				$('#dlg_attach').dialog('close');
				details.show($('#dlg_attach').data('case_id'), false);
			},
			error: function(xhr, status, e) {
				alert('Error:' + xhr.statusText);
			},
			complete: function() {
				$('.ajaxstatus').fadeOut();
			}
		});
	},

	reset: function() {
		$('#attach_type').val('0');
		$('#attach_file').val('');
		$('#attach_comment').val('');
	},

	validate: function() {
		if ($('#attach_file').val() == '')
			return 'Invalid attachment specified';
		return null;
	}
};
