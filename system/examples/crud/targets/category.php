<?php
return [
	'fallback' => 'crud',
	'table' => 'category',
	'id_expr' => '`category_id`',
	'name_expr' => '`category_name`',
	'columns' => [
		'category_name' => [
			'data_type' => PDO::PARAM_STR,
		],
	],
	'actions' => [
		'index' => [
			'columns' => [
				'category_name',
			],
		],
		'view' => [
			'columns' => [
				'category_name',
			],
		],
		'add_affect' => [
			'columns' => [
				'category_name',
			],
		],
		'edit' => [
			'columns' => [
				'category_id',
				'category_name',
			],
		],
		'edit_affect' => [
			'columns' => [
				'category_name',
			],
		],
		'delete' => [
			'columns' => [
				'category_name',
			],
		],
	],
];
