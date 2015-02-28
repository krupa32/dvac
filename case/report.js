var report = {

	init: function() {
		console.log('initializing report');
		$('#dlg_report').dialog({
			autoOpen: false,
			position: { my:'bottom', at:'top-20px', of:'#advanced_report' },
			modal: true,
			title: 'Report',
			width:800,
			height:500
		});
	},

	show: function(arg) {

		$('.ajaxstatus').text('Loading...').show();
		console.log('report:param=' + JSON.stringify(arg));
		$.get('/case/caselist.php', arg, function(data){

			var resp = JSON.parse(data);
			var cases = resp.cases;
			console.log('report.show recv ' + data.length + 'b, query took ' + resp.latency + ' ms');
			console.log('data:' + data);
			$('.ajaxstatus').text('Done').fadeOut();

			// reset table and add header
			var tbl = $('#report');
			tbl.html('<tr>' + 
				'<td class="casenum">Case No.</td>' + 
				'<td class="status">Status</td>' + 
				'<td class="court">Court</td>' + 
				'<td class="investigator">Investigated By</td>' +
				'<td class="petitioner">Petitioner</td>' + 
				'<td class="respondent">Respondent</td>' + 
				'<td class="prayer">Prayer</td>' + 
				'<td class="next_hearing">Next Hearing</td>' + 
				'<td class="tag">Tag</td>' + '</tr>');

			for (i in cases)
				report.add_case(cases[i]);

			$('#dlg_report').dialog('open');

		});
	},

	add_case: function(c) {
		var tbl = $('#report');

		tbl.append('<tr>' + 
			'<td>' + c.case_num + '</td>' +
			'<td>' + c.status + '</td>' +
			'<td>' + c.court+ '</td>' +
			'<td>' + c.investigator + '</td>' +
			'<td>' + c.petitioner + '</td>' +
			'<td>' + c.respondent + '</td>' +
			'<td>' + c.prayer + '</td>' +
			'<td>' + c.next_hearing + '</td>' +
			'<td>' + c.tag + '</td>' +
			'</tr>');
	},

};
