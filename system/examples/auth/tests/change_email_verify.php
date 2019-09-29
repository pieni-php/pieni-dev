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
exec_request('/api/m/auth_m/change_email', [
	'test_params' => [
		'token' => '00000000000000000000000000000001',
	],
	'_POST' => [
		'email' => 'member1a@localhost',
	],
]);
exec_request('/api/auth_m/change_email_verify/1/00000000000000000000000000000001', [
]);
equals_to(
	'/api/auth_m/change_email_verify - auth_m_change_email_succeeded',
	row($dbh, 'SELECT `auth_m_change_email_attempted_id` FROM `auth_m_change_email_succeeded` WHERE `auth_m_change_email_succeeded_id` = 1'),
	[
		'row' => [
			'auth_m_change_email_attempted_id' => 1,
		],
	]
);
equals_to(
	'/api/auth_m/change_email_verify - auth_m.member_email',
	row($dbh, 'SELECT `member_email` FROM `auth_m` WHERE `member_id` = 1'),
	[
		'row' => [
			'member_email' => 'member1a@localhost',
		],
	]
);
