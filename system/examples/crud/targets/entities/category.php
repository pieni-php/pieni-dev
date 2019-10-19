<?php
return [
	'fallback' => 'crud',
	'table' => 'category',
	'columns' => [
		'category_id' => [
			'expr' => '`category_id`',
			'data_type' => PDO::PARAM_INT,
		],
	],
];
