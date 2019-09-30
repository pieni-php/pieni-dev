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
					'category' => [
						'index' => [],
					],
				],
			],
			'api' => [
				'g' => [
					'welcome' => [
						'index' => [],
					],
					'category' => [
						'index' => [],
					],
				],
			],
		],
	],
	'pdo' => [
		'dsn' => 'mysql:dbname=pieni_examples_crud',
		'username' => 'root',
		'password' => '',
	],
];
