<h3>Model '<?php echo $data['model_name']; ?>' not found</h3>
<?php
$all_model_pathes = fallback::get_all_fallback_pathes([
	$data['config']['packages'],
	['models'],
	[$data['model_name'].'_model.php'],
]);
?>
All fallback pathes:
<pre><?php var_export($all_model_pathes); ?></pre>
