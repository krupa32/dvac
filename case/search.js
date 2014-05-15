var search = {
	init: function() {
	},

	show: function(type) {
		$('.page').hide();

		var param = {};

		if (type == 'search') { // user clicked search btn

			param.query = $('#toolbar_category').val();
			if (param.query == 'investigator' || param.query == 'assigned_to')
				param.data = $('#toolbar_data').data('id');
			else
				param.data = $('#toolbar_data').val();

		} else { // 'open' or 'my' navigation link
			param.query = type;
		}

		$.get('/case/search.php', param, function(data){
			//console.log('search.show recv:' + data);
			var div;
			var resp = JSON.parse(data);

			$('#page_search').show();

			$('#resultarea').html('');
			if (resp.length == 0) {
				$('#resultarea').append('<div class="result">No results found</div>');
				return;
			}

			for (i in resp) {
				div = $('<div class="result"></div>').appendTo('#resultarea');
				div.append('<p class="floatright">' + resp[i].status + '</p>');
				div.append('<p class="link"><a href="' + resp[i].id + '">' + resp[i].case_num + '</a></p>');
				div.append('<p class="extra">Petitioner ' + resp[i].petitioner + '<br>' + 
					'Respondent ' + resp[i].respondent + '<br>' +
					'Investigated by ' + resp[i].investigator + '<br>' +
					'Assigned to ' + resp[i].assigned_to + '</p>');
				div.append('<p class="text">' + resp[i].prayer + '</p>');
			}

			$('#page_search a').click(function(){
				details.show($(this).attr('href'));
				return false;
			});

		});

	}
};
