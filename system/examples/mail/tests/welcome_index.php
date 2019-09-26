<?php
equals_to(
	'request /api',
	exec_request('/api', [
		'_POST' => [
			'to' => 'root@localhost',
			'name' => 'John Smith',
		],
	]),
	[
		'to' => 'root@localhost',
		'subject' => 'Hello, John Smith!',
		'message' => 'This is an example mail.'."\n",
		'additional_headers' => '',
		'additional_parameter' => '',
	]
);
