<?php
restore($config, __DIR__.'/../pieni_examples_session.dump');
exec_request('/', ['test_params' => ['microtime' => 0]]);
equals_to(
	'Count is 0 (new session)',
	rows($dbh, 'SELECT `session_data` FROM `session`'),
	[
		'rows' => [
			[
				'session_data' => '{"count":0}',
			],
		],
	]
);
exec_request('/', ['test_params' => ['microtime' => $config['session']['lifetime'] * 1000000 - 1]]);
equals_to(
	'Count is 1 (not expired)',
	rows($dbh, 'SELECT `session_data` FROM `session`'),
	[
		'rows' => [
			[
				'session_data' => '{"count":1}',
			],
		],
	]
);
exec_request('/', ['test_params' => ['microtime' => $config['session']['lifetime'] * 1000000 - 1 + $config['session']['lifetime'] * 1000000]]);
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
