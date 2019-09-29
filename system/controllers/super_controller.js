class super_controller {
	constructor(config, request, target)
	{
		this.config = config;
		this.request = request;
		this.target = target;
	}

	controller(controller_name)
	{
		return new (function(classname){
			return Function('return (' + classname + ')')();
		}(controller_name + '_controller'))(this.config, this.request, this.target);
	}

	href(path, replace_segments = {})
	{
		let href = this.request.base_url;
		const segments = Object.assign({}, this.request, replace_segments);
		if (segments.type !== 'page') href += '/' + segments.type;
		if (segments.language !== this.config.request.languages[0]) href += '/' + segments.language;
		if (segments.actor !== this.config.request.actors[0]) href += '/' + segments.actor;
		href += '/' + path;
		return href;
	}

	show_exception_modal(data)
	{
		$('#exception_modal .error_message').text(data.error_message);
		if (this.config.debug) {
			$('#exception_modal .debug .exception_message').text(data.debug.exception_message);
			$('#exception_modal .debug .exception_file').text(data.debug.exception_file);
			$('#exception_modal .debug .exception_line').text(data.debug.exception_line);
			$('#exception_modal .debug .debug_message').html(data.debug.debug_message);
		}
		$('#exception_modal').modal('show');
	}

	show_alert_modal(message, callback)
	{
		$('#alert_modal .message').html(message);
		$('#alert_modal').off('hidden.bs.modal');
		if (typeof callback === 'function') {
			$('#alert_modal').on('hidden.bs.modal', callback);
		}
		$('#alert_modal').modal('show');
	}
}
