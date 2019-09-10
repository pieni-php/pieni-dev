<?php
return [
	'fallback' => 'auth',
	'actor' => 'a',
	'table' => 'admin',
	'columns' => [
		'admin_name' => [
			'data_type' => PDO::PARAM_STR,
		],
	],
	'token_expire_minutes' => 3,
];
