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
exec_request('/api/m/auth_m/logout', [
]);
equals_to(
	'/api/m/auth_m/logout - auth_session',
	row($dbh, 'SELECT `auth_session_data` FROM `auth_session` WHERE `session_id` = "xxxxxxxx" AND `auth_session_actor` = "m" AND `auth_session_id` = 1'),
	[
		'row' => false,
	]
);
