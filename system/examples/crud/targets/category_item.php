<?php
return array_replace_recursive(
	$this->load_target($config, 'entities/item'),
	[
		'columns' => [
			'parent_ids' => [
				[
					'expr' => '`category_id`',
					'data_type' => PDO::PARAM_INT,
				],
			],
		],
	]
);
