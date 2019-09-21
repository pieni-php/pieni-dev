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
			'api' => [
				'g' => [
					'welcome' => [
						'index' => [],
					],
				],
			],
		],
	],
	'pdo' => [
		'dsn' => 'mysql:dbname=mysql',
		'username' => 'root',
		'password' => '',
	],
];
