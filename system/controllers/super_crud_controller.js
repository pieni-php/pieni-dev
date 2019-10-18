class super_crud_controller extends controller {
	index()
	{
		$('.show_add_modal').click((e) => {
			this.draw_add();
			$('#' + this.target.target + '_add button').prop('disabled', false);
			$('#' + this.target.target + '_add').modal('show');
		});
		$('.show_edit_modal').click((e) => {
			this.draw_edit($(e.target).closest('.row_element').data('id'));
			$('#' + this.target.target + '_edit button').prop('disabled', false);
			$('#' + this.target.target + '_edit').modal('show');
		});
		$('.show_delete_modal').click((e) => {
			this.draw_delete($(e.target).closest('.row_element').data('id'));
			$('#' + this.target.target + '_delete button').prop('disabled', false);
			$('#' + this.target.target + '_delete').modal('show');
		});
		$('#' + this.target.target + '_add').submit((e) => {
			$('#' + this.target.target + '_add').modal('hide');
			$.ajax({
				url: this.href(this.target.target + '/exec_add', {type: 'api'}),
				type: 'post',
				data: $(e.target).serialize(),
				success: (result) => {
					$(e.target).find('button').prop('disabled', true);
					this.draw_index();
				},
				error: (jqXHR) => {
					this.show_exception_modal(JSON.parse(jqXHR.responseText));
				},
			});
			return false;
		});
		$('#' + this.target.target + '_edit').submit((e) => {
			$('#' + this.target.target + '_edit').modal('hide');
			$.ajax({
				url: this.href(this.target.target + '/exec_edit/' + $(e.target).data('id'), {type: 'api'}),
				type: 'post',
				data: $(e.target).serialize(),
				success: (result) => {
					$(e.target).find('button').prop('disabled', true);
					this.draw_index();
				},
				error: (jqXHR) => {
					this.show_exception_modal(JSON.parse(jqXHR.responseText));
				},
			});
			return false;
		});
		$('#' + this.target.target + '_delete').submit((e) => {
			$('#' + this.target.target + '_delete').modal('hide');
			$.ajax({
				url: this.href(this.target.target + '/exec_delete/' + $(e.target).data('id'), {type: 'api'}),
				type: 'post',
				data: $(e.target).serialize(),
				success: (result) => {
					$(e.target).find('button').prop('disabled', true);
					this.draw_index();
				},
				error: (jqXHR) => {
					this.show_exception_modal(JSON.parse(jqXHR.responseText));
				},
			});
			return false;
		});
		this.draw_index();
	}

	view(id)
	{
		$('#show_edit_modal').click(() => {
			this.draw_edit(id);
			$('#' + this.target.target + '_edit button').prop('disabled', false);
			$('#' + this.target.target + '_edit').modal('show');
		});
		$('#show_delete_modal').click((e) => {
			this.draw_delete(id);
			$('#' + this.target.target + '_delete button').prop('disabled', false);
			$('#' + this.target.target + '_delete').modal('show');
		});
		$('#' + this.target.target + '_edit').submit((e) => {
			$('#' + this.target.target + '_edit').modal('hide');
			$.ajax({
				url: this.href(this.target.target + '/exec_edit/' + $(e.target).data('id'), {type: 'api'}),
				type: 'post',
				data: $(e.target).serialize(),
				success: (result) => {
					$(e.target).find('button').prop('disabled', true);
					this.draw_view($(e.target).data('id'));
				},
				error: (jqXHR) => {
					this.show_exception_modal(JSON.parse(jqXHR.responseText));
				},
			});
			return false;
		});
		$('#' + this.target.target + '_delete').submit((e) => {
			$('#' + this.target.target + '_delete').modal('hide');
			$.ajax({
				url: this.href(this.target.target + '/exec_delete/' + $(e.target).data('id'), {type: 'api'}),
				type: 'post',
				data: $(e.target).serialize(),
				success: (result) => {
					$(e.target).find('button').prop('disabled', true);
					location.href = document.referrer;
				},
				error: (jqXHR) => {
					this.show_exception_modal(JSON.parse(jqXHR.responseText));
				},
			});
			return false;
		});
		this.draw_view(id);
		if (this.target.child_names !== undefined) {
			this.target.child_names.forEach(function(child_name){
				$.ajax({
					url: this.href(child_name + '/child_of/' + this.target.target + '/' + id, {type: 'api'}),
					success: (data) => {
						const result = JSON.parse(data);
						const row_template = $('#' + child_name + ' .row_template').eq(0);
						result.forEach(function(row){
							const row_element = row_template.clone(true).removeClass('d-none');
							this.target.children[child_name].as_child_of[this.target.target].action_column_names.child_of.forEach(function(column_name){
								if (column_name === child_name + '_name') {
									row_element.find('[name="' + column_name + '"]').empty().append($('<a>').attr('href', this.href(child_name + '/view/' + row[child_name + '_id'])).text(row[column_name]));
								} else {
									row_element.find('[name="' + column_name + '"]').text(row[column_name]);
								}
							}, this);
							$('#' + child_name + ' table').append(row_element);
						}, this);
					},
					error: (jqXHR) => {
						this.show_exception_modal(JSON.parse(jqXHR.responseText));
					},
				});
			}, this);
		}
	}

	draw_index()
	{
		$.ajax({
			url: this.href(this.target.target, {type: 'api'}),
			success: (data) => {
				const result = JSON.parse(data);
				const row_template = $('.row_template').eq(0);
				$('.row_element').remove();
				result.forEach(function(row){
					const row_element = row_template.clone(true).removeClass('row_template d-none').addClass('row_element');
					row_element.data('id', row[this.target.target + '_id']);
					this.target.action_column_names[this.request.action].forEach(function(column_name){
						if (column_name === this.target.target + '_name') {
							row_element.find('[name="' + column_name + '"]').empty().append($('<a>').attr('href', this.href(this.target.target + '/view/' + row[this.target.target + '_id'])).text(row[column_name]));
						} else {
							row_element.find('[name="' + column_name + '"]').text(row[column_name]);
						}
					}, this);
					$('table').append(row_element);
				}, this);
			},
			error: (jqXHR) => {
				this.show_exception_modal(JSON.parse(jqXHR.responseText));
			},
		});
	}

	draw_view(id) {
		$.ajax({
			url: this.href(this.target.target + '/view/' + id, {type: 'api'}),
			success: (data) => {
				const result = JSON.parse(data);
				$('#' + this.target.target + '_name').text(result[this.target.target + '_name']);
				this.target.action_column_names[this.request.action].forEach(function(column_name){
					$('[name="' + column_name + '"]').text(result[column_name]);
				});
			},
			error: (jqXHR) => {
				this.show_exception_modal(JSON.parse(jqXHR.responseText));
			},
		});
	}

	draw_add(id) {
		this.target.action_column_names['exec_add'].forEach(function(column_name){
			$('[name="' + column_name + '"]').val('');
		});
	}

	draw_edit(id) {
		$('#' + this.target.target + '_edit').data('id', id);
		$.ajax({
			url: this.href(this.target.target + '/edit/' + id, {type: 'api'}),
			success: (data) => {
				const result = JSON.parse(data);
				this.target.action_column_names['edit'].forEach(function(column_name){
					$('[name="' + column_name + '"]').val(result[column_name]);
				});
			},
			error: (jqXHR) => {
				this.show_exception_modal(JSON.parse(jqXHR.responseText));
			},
		});
	}

	draw_delete(id) {
		$('#' + this.target.target + '_delete').data('id', id);
		$.ajax({
			url: this.href(this.target.target + '/delete/' + id, {type: 'api'}),
			success: (data) => {
				const result = JSON.parse(data);
				this.target.action_column_names['delete'].forEach(function(column_name){
					$('[name="' + column_name + '"]').val(result[column_name]);
				});
			},
			error: (jqXHR) => {
				this.show_exception_modal(JSON.parse(jqXHR.responseText));
			},
		});
	}
}
