class super_controller {
	constructor(config, request, target)
	{
		this.config = config;
		this.request = request;
		this.target = target;
	}

	onload()
	{
		$(() => {
			this[this.request.action].apply(this, this.request.argv);
		});
	}

	href(path = '', override = {})
	{
		let href = this.request.base_url;
		const type = typeof override.type === 'string' ? override.type : this.request.type;
		const language = typeof override.language === 'string' ? override.language : this.request.language;
		const actor = typeof override.actor === 'string' ? override.actor : this.request.actor;
		if (type !== 'page') href += '/' + type;
		if (language !== this.config.languages[0]) href += '/' + language;
		if (actor !== this.config.actors[0]) href += '/' + actor;
		if (path !== '') href += '/' + path;
		return href;
	}

	load_error(response_code, debug)
	{
		$('#error_modal .error_type').text(response_code === 500 ? 'Internal Server Error' : response_code === 404 ? 'Not Found' : 'Error');
		$('#error_modal .debug').text(debug);
		$('#error_modal').modal('show');
	}

	load_alert(message, callback)
	{
		$('#alert_modal .message').html(message);
		$('#alert_modal').off('hidden.bs.modal');
		if (typeof callback === 'function') {
			$('#alert_modal').on('hidden.bs.modal', callback);
		}
		$('#alert_modal').modal('show');
	}
}
