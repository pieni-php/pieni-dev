class welcome_controller extends controller {
	index()
	{
		$.ajax({
			url: this.href('api'),
			success: (result) => {
				$('#result').text(JSON.stringify(JSON.parse(result), null, 2));
			},
			error: (jqXHR) => {
				this.show_exception_modal(JSON.parse(jqXHR.responseText));
			},
		});
	}
}
