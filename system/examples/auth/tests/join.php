<?php
restore($config, __DIR__.'/../pieni_examples_auth.dump');

exec_request('/api/auth_m/join', [
	'test_params' => [
		'token' => '00000000000000000000000000000001',
	],
	'_POST' => [
		'email' => 'member1@localhost',
	],
]);
equals_to(
	'/api/auth_m/join - auth_m_register_attempted',
	row($dbh, 'SELECT `member_email` FROM `auth_m_register_attempted` WHERE `auth_m_register_attempted_id` = 1'),
	[
		'row' => [
			'member_email' => 'member1@localhost',
		],
	]
);
