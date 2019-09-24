<?php
return [
	'debug' => true,
	'packages' => ['application', 'system'],
	'request' => [
		'languages' => ['en'],
		'actors' => ['g'],
		'param_patterns' => [
			'page' => [
				'g' => [
					'welcome' => [
						'index' => [],
					],
				],
			],
		],
	],
	'pdo' => [
		'dsn' => 'mysql:dbname=pieni_examples_session',
		'username' => 'root',
		'password' => '',
	],
	'session' => [
		'use' => true,
		'database' => true,
	],
];
