class super_controller {
	constructor(config, request)
	{
		this.config = config;
		this.request = request;
	}

	controller(controller_name)
	{
		return new (function getClass(classname){
			return Function('return (' + classname + ')')();
		}(controller_name + '_controller'))(this.config, this.request);
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
}
