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
exec_request('/api/auth_m/register/1/00000000000000000000000000000001', [
	'_POST' => [
		'password' => 'password1',
		'member_name' => 'Member1',
	],
]);
exec_request('/api/auth_m/login', [
	'_POST' => [
		'email' => 'member1@localhost',
		'password' => 'password1',
	],
]);
exec_request('/api/m/auth_m/unregister', [
]);
equals_to(
	'/api/m/auth_m/unregister - auth_m_unregistered',
	row($dbh, 'SELECT `member_id` FROM `auth_m_unregistered` WHERE `member_id` = 1'),
	[
		'row' => [
			'member_id' => 1,
		],
	]
);
equals_to(
	'/api/m/auth_m/unregister - auth_m',
	row($dbh, 'SELECT `member_email` FROM `auth_m` WHERE `member_id` = 1'),
	[
		'row' => false,
	]
);
