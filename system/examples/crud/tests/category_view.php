<?php
restore($config, __DIR__.'/../pieni_examples_crud.dump');

equals_to(
	'Category view',
	exec_request('/api/category/view/1', [
	]),
	[
		'category_id' => 1,
		'category_name' => 'Category1',
	]
);
