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
exec_request('/api/auth_m/forgot_password', [
	'test_params' => [
		'token' => '00000000000000000000000000000001',
	],
	'_POST' => [
		'email' => 'member1@localhost',
	],
]);
equals_to(
	'/api/auth_m/forgot_password - auth_m_reset_password_attempted',
	row($dbh, 'SELECT `member_id` FROM `auth_m_reset_password_attempted` WHERE `auth_m_reset_password_attempted_id` = 1'),
	[
		'row' => [
			'member_id' => 1,
		],
	]
);
