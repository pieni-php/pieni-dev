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
];
