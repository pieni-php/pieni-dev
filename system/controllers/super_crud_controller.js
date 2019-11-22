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

	view(...ids)
	{
		$.ajax({
			url: this.href(this.request.target + '/view/' + ids.join('/'), {type: 'api'}),
			success: (result) => {
				console.log(result);
			},
			error: (jqXHR) => {
				this.show_exception_modal(JSON.parse(jqXHR.responseText));
			},
		});
	}
}
