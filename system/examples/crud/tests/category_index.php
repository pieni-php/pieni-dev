<?php
restore($config, __DIR__.'/../pieni_examples_crud.dump');

equals_to(
	'Category index',
	exec_request('/api/category', [
	]),
	[
		[
			'category_id' => 1,
			'category_name' => 'Category1',
		],
		[
			'category_id' => 2,
			'category_name' => 'Category2',
		],
	]
);
