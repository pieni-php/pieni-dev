<?php
equals_to(
	'request /api',
	exec_request('/api'),
	[
		[
			'name' => 'auth_socket',
			'dl' => 'auth_socket.so',
		]
	]
);
