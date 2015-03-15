var report = {

	init: function() {
		console.log('initializing report');
		$('#dlg_report').dialog({
			autoOpen: false,
			position: { my:'bottom', at:'top-20px', of:'#advanced_report' },
			modal: true,
			title: 'Report'
			//width:800,
			//height:500
		});
	},

	show: function(arg) {

		$('.ajaxstatus').text('Loading...').show();
		//console.log('report:param=' + JSON.stringify(arg));
		$.get('/case/caselist.php', arg, function(data){

			var resp = JSON.parse(data);
			var csvfilename = resp.cases;
			console.log('report.show recv ' + data.length + 'b, query took ' + resp.latency + ' ms');
			//console.log(data);
			$('.ajaxstatus').text('Done').fadeOut();

			$('#report').html('Report can be downloaded from <a href="/case/tmp/' + resp.cases + '">here</a>');
			$('#dlg_report').dialog('open');

		});
	},

};
