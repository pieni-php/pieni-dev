class welcome_controller extends controller {
	index()
	{
		$('form').submit((e) => {
			$.ajax({
				url: this.href('auth/initialize/' + $('[name="table"]').val() + '/' + $('[name="actor"]').val(), {type: 'api', actor: this.config.actors[0]}),
				type: 'post',
				success: (result) => {
					$(e.target).find('button').prop('disabled', true);
					this.load_alert('Initialize succeeded.');
				},
				error: (jqXHR) => {
					const error = JSON.parse(jqXHR.responseText);
					this.load_error(error.response_code, error.debug);
				},
			});
			return false;
		});
	}
}
