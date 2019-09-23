<h3>Referable '<?php echo $data['referable_file']; ?>' not found</h3>
<?php $segments = array_merge($data['request'], $data['replace_segments']); ?>
<?php
$all_referable_pathes = fallback::get_all_fallback_pathes([
	$data['config']['packages'],
	['referables'],
	[$segments['language'], ''],
	[$segments['actor'], ''],
	[$segments['target'], ''],
	[$data['referable_file']],
]);
?>
All fallback pathes:
<pre><?php var_export($all_referable_pathes); ?></pre>
