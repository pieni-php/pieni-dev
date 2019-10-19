<?php
return [
	'fallback' => 'crud',
	'table' => 'item',
	'columns' => [
		'item_id' => [
			'expr' => '`item_id`',
			'data_type' => PDO::PARAM_INT,
		],
	],
];
