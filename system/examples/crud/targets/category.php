<?php
return [
	'fallback' => 'crud',
	'from_expr' => '`category`',
	'columns' => [
		'category_id' => [
			'data_type' => PDO::PARAM_INT,
		],
		'category_name' => [
			'data_type' => PDO::PARAM_STR,
		],
	],
	'id_column_names' => ['category_id'],
];
