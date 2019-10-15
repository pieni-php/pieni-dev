<?php
return [
	'fallback' => 'auth',
	'actor' => 'm',
	'table' => 'member',
	'columns' => [
		'member_name' => [
			'data_type' => PDO::PARAM_STR,
		],
	],
	'action_column_names' => [
		'register' => [
			'member_name',
		],
	],
	'token_expire_minutes' => 5,
];
