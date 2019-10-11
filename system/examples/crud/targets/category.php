<?php
return [
	'fallback' => 'crud',
	'target' => 'category',
	'table' => 'category',
	'id_expr' => '`category_id`',
	'name_expr' => '`category_name`',
	'child_names' => ['item'],
	'columns' => [
		'id' => [
			'expr' => '`category_id`',
			'data_type' => PDO::PARAM_STR,
		],
		'name' => [
			'expr' => '`category_name`',
			'data_type' => PDO::PARAM_STR,
		],
		'id_and_name' => [
			'expr' => 'CONCAT_WS("-", `category_id`, `category_name`)',
			'data_type' => PDO::PARAM_STR,
		],
	],
	'action_column_names' => [
		'index' => [
			'name',
			'id_and_name',
		],
		'view' => [
			'id_and_name',
		],
	],
];
