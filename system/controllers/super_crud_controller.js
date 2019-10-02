class super_crud_controller extends controller {
	index()
	{
		$.ajax({
			url: this.href('api/' + this.target.target),
			success: (result) => {
				$('#result').text(JSON.stringify(JSON.parse(result), null, 2));
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
				$('#result').text(JSON.stringify(JSON.parse(data), null, 2));
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
			success: (result) => {
				$('#result').text(JSON.stringify(JSON.parse(result), null, 2));
			},
			error: (jqXHR) => {
				this.show_exception_modal(JSON.parse(jqXHR.responseText));
			},
		});
	}
}
