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
			success: (result) => {
				$('#result').text(JSON.stringify(JSON.parse(result), null, 2));
			},
			error: (jqXHR) => {
				this.show_exception_modal(JSON.parse(jqXHR.responseText));
			},
		});
		if (this.target.children !== undefined) {
			this.target.children.forEach(function(child){

				$.ajax({
					url: this.href('api/' + child + '/child_of/' + this.target.target + '/' + id),
					success: (result) => {
						$('#' + child).text(JSON.stringify(JSON.parse(result), null, 2));
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
