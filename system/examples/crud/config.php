<?php
return [
	'pdo' => [
		'dsn' => 'mysql:dbname=pieni_examples_crud',
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
				'category' => [
					'index' => [
						'argc' => 0,
					],
					'view' => [
						'argc' => 1,
					],
					'add' => [
						'argc' => 0,
					],
					'edit' => [
						'argc' => 1,
					],
					'delete' => [
						'argc' => 1,
					],
				],
				'item' => [
					'index' => [
						'argc' => 0,
					],
					'view' => [
						'argc' => 1,
					],
				],
			],
		],
		'api' => [
			'g' => [
				'category' => [
					'index' => [
						'argc' => 0,
					],
					'view' => [
						'argc' => 1,
					],
					'add_affect' => [
						'argc' => 0,
					],
					'edit' => [
						'argc' => 1,
					],
					'edit_affect' => [
						'argc' => 1,
					],
					'delete' => [
						'argc' => 1,
					],
					'delete_affect' => [
						'argc' => 1,
					],
				],
				'item' => [
					'index' => [
						'argc' => 0,
					],
					'view' => [
						'argc' => 1,
					],
				],
			],
		],
	],
];
