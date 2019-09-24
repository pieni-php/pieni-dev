<div class="container">
<?php $_SESSION['a'] = isset($_SESSION['a']) ? $_SESSION['a'] + 1 : 0; ?>
<pre>
<?php var_export($_SESSION); ?>
</pre>
<?php $_SESSION['b'] = 123; ?>
<?php $_SESSION['auth'] = [
	'g' => [
		'id' => 0,
		'data' => [
			'offset' => 20,
		],
	],
	'm' => [
		'id' => 1,
		'data' => [
			'offset' => 0,
		],
	],
]; ?>
</div>
