<?php
(function(){
	$index_config = [
		'packages' => ['application', 'system'],
	];
	if (isset($GLOBALS['test_index_config'])) {
		$index_config = array_replace_recursive($index_config, $GLOBALS['test_index_config']);
	} elseif (file_exists('./development_config.php')) {
		$index_config = array_replace_recursive($index_config, require './development_config.php');
	}
	require_once './fallback.php';
	$super_dispatcher_path = fallback::get_fallback_path([
		$index_config['packages'],
		['super_dispatcher.php'],
	]);
	require_once $super_dispatcher_path;
	$dispatcher_path = fallback::get_fallback_path([
		$index_config['packages'],
		['dispatcher.php'],
	]);
	if ($dispatcher_path !== null) {
		require_once $dispatcher_path;
	} else {
		class_alias('super_dispatcher', 'dispatcher');
	}
	(new dispatcher())->dispatch($index_config);
})();
