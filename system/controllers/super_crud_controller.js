class super_crud_controller extends controller {
	index()
	{
		$.ajax({
			url: this.href(this.target.target, {type: 'api'}),
			success: (data) => {
				const result = JSON.parse(data);
				const row_template = $('#row_template');
				result.forEach(function(row){
					const row_element = row_template.clone(true).removeClass('d-none');
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

	view(id)
	{
		$('#show_edit_modal').click(() => {
			this.draw_edit(id);
			$('#' + this.target.target + '_edit button').prop('disabled', false);
			$('#' + this.target.target + '_edit').modal('show');
		});
		$('#' + this.target.target + '_edit').submit((e) => {
			$('#' + this.target.target + '_edit').modal('hide');
			$.ajax({
				url: this.href(this.target.target + '/exec_edit/' + id, {type: 'api'}),
				type: 'post',
				data: $(e.target).serialize(),
				success: (result) => {
					$(e.target).find('button').prop('disabled', true);
					this.draw_view(id);
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
						const row_template = $('#' + child_name + ' .row_template');
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

	draw_edit(id) {
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
}
