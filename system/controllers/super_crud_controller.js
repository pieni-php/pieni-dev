class super_crud_controller extends controller {
	child_of(parent, parent_id)
	{
		$.ajax({
			url: this.href(this.target.table + '/child_of/' + parent + '/' + parent_id, {type: 'api'}),
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

	index()
	{
		$.ajax({
			url: this.href(this.target.table, {type: 'api'}),
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
		if (this.target.children !== undefined) {
			this.children = {};
			for (const child_name in this.target.children) {
				this.children[child_name] = new (function (classname){return Function('return (' + classname + ')')()}(this.target['children'][child_name]['controller_class']))(
					this.config,
					this.request,
					this.target['children'][child_name]
				);
				this.children[child_name].child_of(this.target.table, id);
			}
		}
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
			success: (data) => {
				const result = JSON.parse(data);
				$('[name="_name"]').text(result.row['_name']);
				this.target.actions[this.request.action].columns.forEach((column_name) => {
					$('[name="' + column_name + '"]').text(result.row[column_name]).val(result.row[column_name]);
				});
			},
			error: (jqXHR) => {
				const error = JSON.parse(jqXHR.responseText);
				this.load_error(error.response_code, error.debug);
			},
		});
	}
}
