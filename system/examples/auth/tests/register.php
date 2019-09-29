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
equals_to(
	'/api/auth_m/register - member',
	row($dbh, 'SELECT `member_name` FROM `member` WHERE `member_id` = 1'),
	[
		'row' => [
			'member_name' => 'Member1',
		],
	]
);
equals_to(
	'/api/auth_m/register - auth_m_register_succeeded',
	row($dbh, 'SELECT `auth_m_register_attempted_id`, `member_id` FROM `auth_m_register_succeeded` WHERE `auth_m_register_succeeded_id` = 1'),
	[
		'row' => [
			'auth_m_register_attempted_id' => 1,
			'member_id' => 1,
		],
	]
);
equals_to(
	'/api/auth_m/register - auth_m.member_email',
	row($dbh, 'SELECT `member_email` FROM `auth_m` WHERE `member_id` = 1'),
	[
		'row' => [
			'member_email' => 'member1@localhost',
		],
	]
);
password_hash_matches(
	'/api/auth_m/register - auth_m.member_password',
	row($dbh, 'SELECT `member_password` FROM `auth_m` WHERE `member_id` = 1')['row']['member_password'],
	'password1'
);
