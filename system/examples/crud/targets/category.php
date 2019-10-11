<?php
return [
	'fallback' => 'crud',
	'target' => 'category',
	'table' => 'category',
	'child_names' => ['item'],
	'columns' => [
		'category_id' => [
			'expr' => '`category_id`',
			'data_type' => PDO::PARAM_STR,
		],
		'category_name' => [
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
			'category_name',
			'id_and_name',
		],
		'view' => [
			'id_and_name',
		],
	],
];
