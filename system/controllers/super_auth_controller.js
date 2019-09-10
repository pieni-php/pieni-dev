class super_auth_controller extends controller {
	join()
	{
		$('form').submit((e) => {
			$.ajax({
				url: this.href(this.request.target + '/join', {type: 'api'}),
				type: 'post',
				data: $(e.target).serialize(),
				success: (result) => {
					$(e.target).find('button').prop('disabled', true);
					this.load_alert('Please check your email.');
				},
				error: (jqXHR) => {
					const error = JSON.parse(jqXHR.responseText);
					this.load_error(error.response_code, error.debug);
				},
			});
			return false;
		});
	}

	register()
	{
		$('form').submit((e) => {
			$.ajax({
				url: this.href(this.request.target + '/register/' + this.request.argv[0] + '/' + this.request.argv[1], {type: 'api'}),
				type: 'post',
				data: $(e.target).serialize(),
				success: (result) => {
					$(e.target).find('button').prop('disabled', true);
					this.load_alert('Registration has been completed.', () => {
						location.href = this.href(this.request.target + '/login');
					});
				},
				error: (jqXHR) => {
					const error = JSON.parse(jqXHR.responseText);
					this.load_error(error.response_code, error.debug);
				},
			});
			return false;
		});
	}

	login()
	{
		$('form').submit((e) => {
			$.ajax({
				url: this.href(this.request.target + '/login', {type: 'api'}),
				type: 'post',
				data: $(e.target).serialize(),
				success: (result) => {
					$(e.target).find('button').prop('disabled', true);
					location.href = this.href('', {actor: 'm'});
				},
				error: (jqXHR) => {
					const error = JSON.parse(jqXHR.responseText);
					this.load_error(error.response_code, error.debug);
				},
			});
			return false;
		});
	}

	change_email_verify()
	{
		$(() => {
			$.ajax({
				url: this.href(this.request.target + '/change_email_verify/' + this.request.argv[0] + '/' + this.request.argv[1], {type: 'api'}),
				type: 'post',
				success: (result) => {
					this.load_alert('The email has been changed.', () => {
						location.href = this.href(this.request.target + '/login');
					});
				},
				error: (jqXHR) => {
					const error = JSON.parse(jqXHR.responseText);
					this.load_error(error.response_code, error.debug);
				},
			});
		});
	}

	forgot_password()
	{
		$('form').submit((e) => {
			$.ajax({
				url: this.href(this.request.target + '/forgot_password', {type: 'api'}),
				type: 'post',
				data: $(e.target).serialize(),
				success: (result) => {
					$(e.target).find('button').prop('disabled', true);
					this.load_alert('Please check your email.');
				},
				error: (jqXHR) => {
					const error = JSON.parse(jqXHR.responseText);
					this.load_error(error.response_code, error.debug);
				},
			});
			return false;
		});
	}

	reset_password()
	{
		$('form').submit((e) => {
			$.ajax({
				url: this.href(this.request.target + '/reset_password/' + this.request.argv[0] + '/' + this.request.argv[1], {type: 'api'}),
				type: 'post',
				data: $(e.target).serialize(),
				success: (result) => {
					$(e.target).find('button').prop('disabled', true);
					this.load_alert('The password has been reset.', () => {
						location.href = this.href(this.request.target + '/login');
					});
				},
				error: (jqXHR) => {
					const error = JSON.parse(jqXHR.responseText);
					this.load_error(error.response_code, error.debug);
				},
			});
			return false;
		});
	}

	unregister()
	{
		$('form').submit((e) => {
			$.ajax({
				url: this.href(this.request.target + '/unregister', {type: 'api'}),
				type: 'post',
				data: $(e.target).serialize(),
				success: (result) => {
					$(e.target).find('button').prop('disabled', true);
					this.load_alert('Unregistration has been completed.', () => {
						location.href = this.href('', {actor: 'g'});
					});
				},
				error: (jqXHR) => {
					const error = JSON.parse(jqXHR.responseText);
					this.load_error(error.response_code, error.debug);
				},
			});
			return false;
		});
	}

	logout()
	{
		$('form').submit((e) => {
			$.ajax({
				url: this.href(this.request.target + '/logout', {type: 'api'}),
				type: 'post',
				data: $(e.target).serialize(),
				success: (result) => {
					$(e.target).find('button').prop('disabled', true);
					location.href = this.href('', {actor: 'g'});
				},
				error: (jqXHR) => {
					const error = JSON.parse(jqXHR.responseText);
					this.load_error(error.response_code, error.debug);
				},
			});
			return false;
		});
	}

	change_email()
	{
		$('form').submit((e) => {
			$.ajax({
				url: this.href(this.request.target + '/change_email', {type: 'api'}),
				type: 'post',
				data: $(e.target).serialize(),
				success: (result) => {
					$(e.target).find('button').prop('disabled', true);
					this.load_alert('Please check your email.');
				},
				error: (jqXHR) => {
					const error = JSON.parse(jqXHR.responseText);
					this.load_error(error.response_code, error.debug);
				},
			});
			return false;
		});
	}

	change_password()
	{
		$('form').submit((e) => {
			$.ajax({
				url: this.href(this.request.target + '/change_password', {type: 'api'}),
				type: 'post',
				data: $(e.target).serialize(),
				success: (result) => {
					$(e.target).find('button').prop('disabled', true);
					this.load_alert('The password has been changed.', () => {
						location.href = this.href(this.request.target + '/login', {actor: 'g'});
					});
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
