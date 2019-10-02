class super_crud_controller extends controller {
	index()
	{
		$.ajax({
			url: this.href('api/' + this.target.target),
			success: (data) => {
				const result = JSON.parse(data);
				const row_template = $('#row_template');
				result.forEach(function(row){
					const row_element = row_template.clone(true).removeClass('d-none');
					['name'].concat(Object.keys(this.target.columns)).forEach(function(column_name){
						row_element.find('[name="' + column_name + '"]').text(row[column_name]);
					});
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
		$.ajax({
			url: this.href('api/' + this.target.target + '/view/' + id),
			success: (data) => {
				const result = JSON.parse(data);
				$('[name="name"]').text(result.name);
				Object.keys(this.target.columns).forEach(function(column_name){
					$('[name="' + column_name + '"]').text(result[column_name]);
				});
			},
			error: (jqXHR) => {
				this.show_exception_modal(JSON.parse(jqXHR.responseText));
			},
		});
		if (this.target.child_names !== undefined) {
			this.target.child_names.forEach(function(child_name){

				$.ajax({
					url: this.href('api/' + child_name + '/child_of/' + this.target.target + '/' + id),
					success: (result) => {
						$('#' + child_name).text(JSON.stringify(JSON.parse(result), null, 2));
					},
					error: (jqXHR) => {
						this.show_exception_modal(JSON.parse(jqXHR.responseText));
					},
				});

			}, this);
		}
	}

	child_of(parent, parent_id)
	{
		$.ajax({
			url: this.href('api/' + this.target.target + '/child_of/' + parent + '/' + parent_id),
			success: (data) => {
				const result = JSON.parse(data);
				const row_template = $('#row_template');
				result.forEach(function(row){
					const row_element = row_template.clone(true).removeClass('d-none');
					['name'].concat(Object.keys(this.target.columns)).forEach(function(column_name){
						row_element.find('[name="' + column_name + '"]').text(row[column_name]);
					});
					$('table').append(row_element);
				}, this);
			},
			error: (jqXHR) => {
				this.show_exception_modal(JSON.parse(jqXHR.responseText));
			},
		});
	}
}
