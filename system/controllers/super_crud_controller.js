class super_crud_controller extends controller {
	index()
	{
		$.ajax({
			url: this.href(this.request.target, {type: 'api'}),
			success: (result) => {
				console.log(result);
			},
			error: (jqXHR) => {
				this.show_exception_modal(JSON.parse(jqXHR.responseText));
			},
		});
	}

	view(id)
	{
		$.ajax({
			url: this.href(this.request.target + '/view/' + id, {type: 'api'}),
			success: (result) => {
				console.log(result);
			},
			error: (jqXHR) => {
				this.show_exception_modal(JSON.parse(jqXHR.responseText));
			},
		});
	}
}
