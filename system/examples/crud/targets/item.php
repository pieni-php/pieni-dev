<?php
return [
	'fallback' => 'crud',
	'table' => 'item',
	'id_expr' => '`item_id`',
	'name_expr' => '`item_name`',
	'join_tables' => [
		'NATURAL JOIN `category`',
	],
	'columns' => [
		'category_id' => [
			'data_type' => PDO::PARAM_INT,
		],
		'category_name' => [
			'data_type' => PDO::PARAM_STR,
		],
		'item_name' => [
			'data_type' => PDO::PARAM_STR,
		],
	],
	'actions' => [
		'index' => [
			'columns' => [
				'category_name',
				'item_name',
			],
		],
		'view' => [
			'columns' => [
				'category_id',
				'category_name',
				'item_name',
			],
		],
	],
];
