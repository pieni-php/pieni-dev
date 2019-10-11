<?php
return [
	'fallback' => 'crud',
	'target' => 'item',
	'table' => 'item',
	'id_expr' => '`item_id`',
	'name_expr' => '`item_name`',
	'join_tables' => [
		'category' => [],
	],
	'columns' => [
		'id' => [
			'expr' => '`item_id`',
			'data_type' => PDO::PARAM_STR,
		],
		'name' => [
			'expr' => '`item_name`',
			'data_type' => PDO::PARAM_STR,
		],
		'category_name' => [
			'data_type' => PDO::PARAM_STR,
		],
	],
	'action_column_names' => [
		'index' => [
			'category_name',
			'name',
		],
		'view' => [
			'category_name',
		],
	],
	'as_child_of' => [
		'category' => [
			'action_column_names' => [
				'child_of' => [
					'name',
				],
			],
		],
	],
];
