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
	'token_expire_minutes' => 5,
];
