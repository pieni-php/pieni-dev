<h3>Mail '<?php echo $data['mail_name']; ?>' not found</h3>
<?php $segments = array_merge($data['request'], $data['replace_segments']); ?>
<?php
$all_mail_pathes = fallback::get_all_fallback_pathes([
	$data['config']['packages'],
	['mails'],
	[$segments['language'], ''],
	[$segments['actor'], ''],
	[$segments['target'], ''],
	[$data['mail_name'].'.php'],
]);
?>
All fallback pathes:
<pre><?php var_export($all_mail_pathes); ?></pre>
