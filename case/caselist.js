var caselist = {

	/* starting item for next fetch.
	 * during each fetch, the number of items specified
	 * in config.php is returned. this value is incremented
	 * by the num items returned by the server.
	 */
	start_item: 0,

	init: function() {
		$('#caselist_more').click(function(){
			caselist.show(history.state.arg, true, false);
		});
	},

	show: function(arg, more, push) {
		//console.log('caselist.show type:' + type);

		$('.page').hide();
		$('#page_caselist').show();
		navigation.update_hilite(arg.type);

		if (!more)
			$('#caselistarea').html('');

		/* if called due to browser back/fwd button,
		 * no need to refresh anything.
		 */
		if (!more && !push)
			return;

		if (!more) {
			caselist.start_item = 0;
			$('#caselist_more').show();
		}

		if (push)
			history.pushState({ page:'caselist', arg:arg }, '', '#caselist?' + arg.type);

		/* send the start_item also to the server */
		arg.start_item = caselist.start_item;

		$('.ajaxstatus').text('Loading...').show();
		$.get('/case/caselist.php', arg, function(data){
			if (!more)
				$('#caselistarea').html('');

			var resp = JSON.parse(data);
			var cases = resp.cases;
			console.log('caselist.show recv ' + data.length + 'b, query took ' + resp.latency + ' ms');
			//console.log('data:' + data);
			$('.ajaxstatus').text('Done').fadeOut();

			if (cases.length == 0) {
				$('#caselistarea').append('<div class="aligncenter cl_data">No more items found</div>');
				$('#caselist_more').hide();
				return;
			}

			if (cases.length < 10) {
				/* WARNING: currently this number is hard-coded.
				 * If num_items_per_fetch is changed in config.php,
				 * this number should also be updated.
				 */
				$('#caselist_more').hide();
			}

			for (i in cases)
				caselist.add_case(cases[i]);

			caselist.start_item += cases.length;

			$('#page_caselist a.caselink').click(function(e){
				details.show($(this).attr('href'), true);
				e.preventDefault();
			});

		});
	},

	add_case: function(c) {
		// trim the petitioner, respondent
		if (c.petitioner.length > 60)
			c.petitioner = c.petitioner.substr(0, 60) + '...';

		var div = $('<div class="cl_data"></div>').appendTo('#caselistarea');
		div.append('<div class="cl_details"><a class="caselink" href="' + c.id + '">' + c.case_num + '</a></div>');
		div.append('<div class="cl_next_hearing">' + c.next_hearing + '</div>');
		div.append('<div class="cl_investigator">' + c.investigator + '</div>');
		div.append('<div class="cl_location">' + c.location + '</div>');

		if (c.status == 'PENDING_IN_COURT')
			div.find('a').addClass('green');
		else if (c.status == 'PENDING_WITH_DVAC')
			div.find('a').addClass('red');
		else
			div.find('a').addClass('gray');
	},


};
