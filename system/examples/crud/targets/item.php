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
		'category_name' => [
			'data_type' => PDO::PARAM_STR,
		],
	],
	'action_column_names' => [
		'index' => [
			'name',
			'category_name',
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
