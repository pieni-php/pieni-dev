class welcome_controller extends controller {
	index()
	{
		$.ajax({
			url: this.href('api'),
			success: (result) => {
				$('#result').text(JSON.stringify(JSON.parse(result), null, 2));
			},
			error: (jqXHR) => {
				const error = JSON.parse(jqXHR.responseText);
				this.load_error(error.response_code, error.debug);
			},
		});
	}
}
