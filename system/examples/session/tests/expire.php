<?php
restore($config, __DIR__.'/../pieni_examples_session.dump');
exec_request('/', ['microtime' => 0]);
equals_to(
	'Count is 0',
	rows($dbh, 'SELECT `session_data` FROM `session`'),
	[
		'rows' => [
			[
				'session_data' => '{"count":0}',
			],
		],
	]
);
exec_request('/', ['microtime' => 86400 * 1000000]);
equals_to(
	'Count is 0 (expired)',
	rows($dbh, 'SELECT `session_data` FROM `session`'),
	[
		'rows' => [
			[
				'session_data' => '{"count":0}',
			],
		],
	]
);
