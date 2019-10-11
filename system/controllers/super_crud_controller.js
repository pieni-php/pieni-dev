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
						if (column_name === 'name') {
							row_element.find('[name="name"]').empty().append($('<a>').attr('href', this.href(this.target.target + '/view/' + row['id'])).text(row['name']));
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
		$('#' + this.target.target + '_edit').submit((e) => {
			$.ajax({
				url: this.href(this.target.target + '/edit/' + id, {type: 'api'}),
				type: 'post',
				data: $(e.target).serialize(),
				success: (result) => {
					$(e.target).find('button').prop('disabled', true);
					this.draw_view(id);
					this.show_alert_modal('Please check your email.');
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
							row_element.find('[name="name"]').empty().append($('<a>').attr('href', this.href(child_name + '/view/' + row['id'])).text(row['name']));;
							this.target.children[child_name].as_child_of[this.target.target].action_column_names.child_of.forEach(function(column_name){
								row_element.find('[name="' + column_name + '"]').text(row[column_name]);
							});
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
				$('#' + this.target.target + '_name').text(result.name);
				this.target.action_column_names[this.request.action].forEach(function(column_name){
					$('[name="' + column_name + '"]').text(result[column_name]);
				});
			},
			error: (jqXHR) => {
				this.show_exception_modal(JSON.parse(jqXHR.responseText));
			},
		});
	}
}
