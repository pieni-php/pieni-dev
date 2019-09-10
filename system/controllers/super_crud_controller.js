class super_crud_controller extends controller {
	index()
	{
		$.ajax({
			url: this.href(this.request.target, {type: 'api'}),
			success: (result) => {
				const rows = JSON.parse(result);
				rows.forEach((row) => {
					const clone = $('.d-none').eq(0).clone(true);
					clone.find('[name="_name"]').text(row['_name']);
					Object.keys(this.target.columns).forEach((column_name) => {
						clone.find('[name="' + column_name + '"]').text(row[column_name]);
					});
					const action_links = clone.find('a');
					for (let i = 0; i < action_links.length; i++) {
						action_links.eq(i).attr('href', action_links.eq(i).attr('href') + '/' + row['_id']);
					}
					clone.removeClass('d-none');
					clone.appendTo($('.d-none').eq(0).parent());
				});
			},
			error: (jqXHR) => {
				const error = JSON.parse(jqXHR.responseText);
				this.load_error(error.response_code, error.debug);
			},
		});
	}

	view(id)
	{
		this.get_row(id);
	}

	add()
	{
		$('form').submit((e) => {
			$.ajax({
				url: this.href(this.request.target + '/' + this.request.action + '_affect', {type: 'api'}),
				type: 'post',
				data: $(e.target).serialize(),
				success: (result) => {
					$(e.target).find('button').prop('disabled', true);
					this.load_alert('Saved.', () => {
						history.back();
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

	edit(id)
	{
		this.get_row(id);
		$('form').submit((e) => {
			$.ajax({
				url: this.href(this.request.target + '/' + this.request.action + '_affect/' + id, {type: 'api'}),
				type: 'post',
				data: $(e.target).serialize(),
				success: (result) => {
					$(e.target).find('button').prop('disabled', true);
					this.load_alert('Saved.', () => {
						history.back();
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

	delete(id)
	{
		this.get_row(id);
		$('form').submit((e) => {
			$.ajax({
				url: this.href(this.request.target + '/' + this.request.action + '_affect/' + id, {type: 'api'}),
				type: 'post',
				success: (result) => {
					$(e.target).find('button').prop('disabled', true);
					this.load_alert('Deleted.', () => {
						history.back();
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

	get_row(id)
	{
		$.ajax({
			url: this.href(this.request.target + '/' + this.request.action + '/' + id, {type: 'api'}),
			success: (result) => {
				const row = JSON.parse(result);
				$('[name="_name"]').text(row['_name']);
				this.target.actions[this.request.action].columns.forEach((column_name) => {
					$('[name="' + column_name + '"]').text(row[column_name]).val(row[column_name]);
				});
			},
			error: (jqXHR) => {
				const error = JSON.parse(jqXHR.responseText);
				this.load_error(error.response_code, error.debug);
			},
		});
	}
}
