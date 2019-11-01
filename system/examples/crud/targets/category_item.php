<?php
return array_replace_recursive(
	$this->load_target($config, 'entities/item'),
	[
		'parent_id_column_names' => ['category_id'],
	]
);
