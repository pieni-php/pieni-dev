<?php
return [
	'fallback' => 'crud',
	'table' => 'item',
	'columns' => [
		'category_id' => [
			'expr' => '`category_id`',
			'data_type' => PDO::PARAM_INT,
		],
		'item_id' => [
			'expr' => '`item_id`',
			'data_type' => PDO::PARAM_INT,
		],
	],
];
