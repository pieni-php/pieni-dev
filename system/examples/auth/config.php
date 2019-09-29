<?php
return [
	'debug' => true,
	'packages' => ['application', 'system'],
	'request' => [
		'languages' => ['en'],
		'actors' => ['g', 'm'],
		'param_patterns' => [
			'page' => [
				'g' => [
					'welcome' => [
						'index' => [],
					],
					'auth_m' => [
						'join' => [],
						'register' => ['/^[1-9][0-9]*$/', '/^[0-9a-f]+$/'],
						'login' => [],
						'change_email_verify' => ['/^[1-9][0-9]*$/', '/^[0-9a-f]+$/'],
						'forgot_password' => [],
						'reset_password' => ['/^[1-9][0-9]*$/', '/^[0-9a-f]+$/'],
					],
				],
				'm' => [
					'welcome' => [
						'index' => [],
					],
					'auth_m' => [
						'unregister' => [],
						'logout' => [],
						'change_email' => [],
						'change_password' => [],
					],
				],
			],
			'api' => [
				'g' => [
					'auth_m' => [
						'join' => [],
						'register' => ['/^[1-9][0-9]*$/', '/^[0-9a-f]+$/'],
						'login' => [],
						'change_email_verify' => ['/^[1-9][0-9]*$/', '/^[0-9a-f]+$/'],
						'forgot_password' => [],
						'reset_password' => ['/^[1-9][0-9]*$/', '/^[0-9a-f]+$/'],
					],
				],
				'm' => [
					'auth_m' => [
						'unregister' => [],
						'logout' => [],
						'change_email' => [],
						'change_password' => [],
					],
				],
			],
		],
	],
	'pdo' => [
		'dsn' => 'mysql:dbname=pieni_examples_auth',
		'username' => 'root',
		'password' => '',
	],
	'session' => [
		'use' => true,
		'lifetime' => 60,
		'database' => true,
	],
];
