class welcome_controller extends controller {
	index()
	{
		$('form').submit($.proxy(function(e){
			$.ajax({
				url: this.href('api'),
				method: 'post',
				data: $(e.target).serialize(),
				success: (result) => {
					$('#result').text(JSON.stringify(JSON.parse(result), null, 2));
				},
				error: (jqXHR) => {
					this.show_exception_modal(JSON.parse(jqXHR.responseText));
				},
			});
			return false;
		}, this));
	}
}
