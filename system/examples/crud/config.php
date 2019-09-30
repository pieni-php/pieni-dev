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
						'view' => ['/^[1-9]\d*$/'],
					],
					'item' => [
						'index' => [],
						'view' => ['/^[1-9]\d*$/'],
					],
				],
			],
			'api' => [
				'g' => [
					'category' => [
						'index' => [],
						'view' => ['/^[1-9]\d*$/'],
					],
					'item' => [
						'index' => [],
						'view' => ['/^[1-9]\d*$/'],
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
