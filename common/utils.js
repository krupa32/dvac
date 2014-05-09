var utils = {
	dynamic_combo: function(sel, url, cb) {
		$.get(url, null, function(data){
			var resp = JSON.parse(data);
			for (i in resp)
				$(sel).append('<option value="' + resp[i].value + '">' + resp[i].name + '</option>');

			cb();
		});
	}
};
