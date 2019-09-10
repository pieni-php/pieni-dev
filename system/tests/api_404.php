<?php
return [
	'_server' => [
		'PATH_INFO' => '/api',
	],
	'expected' => [
		'response' => json_encode([
			'response_code' => 404,
			'debug' => 'Invalid target \'welcome\'',
		], JSON_UNESCAPED_UNICODE),
	],
];
