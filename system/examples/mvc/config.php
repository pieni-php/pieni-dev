<?php
return [
	'packages' => ['application', 'system'],
	'pdo' => [
		'dsn' => 'mysql:dbname=mysql',
		'username' => 'root',
		'password' => '',
	],
	'debug' => true,
	'languages' => ['en'],
	'actors' => ['g'],
	'actions' => [
		'page' => [
			'g' => [
				'welcome' => [
					'index' => [
						'argc' => 0,
					],
				],
			],
		],
		'api' => [
			'g' => [
				'welcome' => [
					'index' => [
						'argc' => 0,
					],
				],
			],
		],
	],
];
