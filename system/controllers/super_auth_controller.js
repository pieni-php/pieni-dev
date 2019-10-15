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
					this.show_alert_modal('Please check your email.');
				},
				error: (jqXHR) => {
					this.show_exception_modal(JSON.parse(jqXHR.responseText));
				},
			});
			return false;
		});
	}

	register()
	{
		$('form').submit((e) => {
			$.ajax({
				url: this.href(this.request.target + '/register/' + this.request.params[0] + '/' + this.request.params[1], {type: 'api'}),
				type: 'post',
				data: $(e.target).serialize(),
				success: (result) => {
					$(e.target).find('button').prop('disabled', true);
					this.show_alert_modal('Registration has been completed.', () => {
						location.href = this.href(this.request.target + '/login');
					});
				},
				error: (jqXHR) => {
					this.show_exception_modal(JSON.parse(jqXHR.responseText));
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
					location.href = this.href('', {actor: this.target.actor});
				},
				error: (jqXHR) => {
					this.show_exception_modal(JSON.parse(jqXHR.responseText));
				},
			});
			return false;
		});
	}

	change_email_verify()
	{
		$(() => {
			$.ajax({
				url: this.href(this.request.target + '/change_email_verify/' + this.request.params[0] + '/' + this.request.params[1], {type: 'api'}),
				type: 'post',
				success: (result) => {
					this.show_alert_modal('The email has been changed.', () => {
						location.href = this.href(this.request.target + '/login');
					});
				},
				error: (jqXHR) => {
					this.show_exception_modal(JSON.parse(jqXHR.responseText));
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
					this.show_alert_modal('Please check your email.');
				},
				error: (jqXHR) => {
					this.show_exception_modal(JSON.parse(jqXHR.responseText));
				},
			});
			return false;
		});
	}

	reset_password()
	{
		$('form').submit((e) => {
			$.ajax({
				url: this.href(this.request.target + '/reset_password/' + this.request.params[0] + '/' + this.request.params[1], {type: 'api'}),
				type: 'post',
				data: $(e.target).serialize(),
				success: (result) => {
					$(e.target).find('button').prop('disabled', true);
					this.show_alert_modal('The password has been reset.', () => {
						location.href = this.href(this.request.target + '/login');
					});
				},
				error: (jqXHR) => {
					this.show_exception_modal(JSON.parse(jqXHR.responseText));
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
					this.show_alert_modal('Unregistration has been completed.', () => {
						location.href = this.href('', {actor: 'g'});
					});
				},
				error: (jqXHR) => {
					this.show_exception_modal(JSON.parse(jqXHR.responseText));
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
					this.show_exception_modal(JSON.parse(jqXHR.responseText));
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
					this.show_alert_modal('Please check your email.');
				},
				error: (jqXHR) => {
					this.show_exception_modal(JSON.parse(jqXHR.responseText));
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
					this.show_alert_modal('The password has been changed.', () => {
						location.href = this.href(this.request.target + '/login', {actor: 'g'});
					});
				},
				error: (jqXHR) => {
					this.show_exception_modal(JSON.parse(jqXHR.responseText));
				},
			});
			return false;
		});
	}
}
