<h3>View '<?php echo $data['view_name']; ?>' not found</h3>
<?php $segments = array_merge($data['request'], $data['replace_segments']); ?>
<?php
$all_view_pathes = fallback::get_all_fallback_pathes([
	$data['config']['packages'],
	['views'],
	[$segments['language'], ''],
	[$segments['actor'], ''],
	[$segments['target'], ''],
	isset($data['target']['fallback']) ? [$segments['target'], $data['target']['fallback'], ''] : [$segments['target'], ''],
	[$data['view_name'].'.php'],
]);
?>
All fallback pathes:
<pre><?php var_export($all_view_pathes); ?></pre>
