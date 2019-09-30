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
	}
}
