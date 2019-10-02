<?php
return [
	'fallback' => 'crud',
	'target' => 'item',
	'table' => 'item',
	'id_expr' => '`item_id`',
	'name_expr' => '`item_name`',
	'columns' => [
		'category_id' => [
			'data_type' => PDO::PARAM_INT,
		],
	],
];
