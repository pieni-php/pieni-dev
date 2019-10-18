<?php
return [
	'fallback' => 'crud',
	'target' => 'item',
	'table' => 'item',
	'join_tables' => [
		'category' => [],
	],
	'columns' => [
		'item_id' => [
			'expr' => '`item_id`',
			'data_type' => PDO::PARAM_STR,
		],
		'item_name' => [
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
			'item_name',
		],
		'view' => [
			'category_name',
		],
		'edit' => [
			'item_name',
		],
		'exec_edit' => [
			'item_name',
		],
		'delete' => [
			'item_id',
			'item_name',
		],
	],
	'as_child_of' => [
		'category' => [
			'action_column_names' => [
				'child_of' => [
					'item_name',
				],
			],
		],
	],
];
