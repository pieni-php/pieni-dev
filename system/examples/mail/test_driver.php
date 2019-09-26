<?php
(function($argv){
	$_SERVER = [
		'PATH_INFO' => $argv[1],
		'REQUEST_SCHEME' => 'https',
		'SERVER_NAME' => 'localhost',
		'SCRIPT_NAME' => 'index.php',
	];
	$_COOKIE = [
		'PHPSESSID' => 'xxxxxxxx',
	];
	if (isset($argv[2])) {
		$replace_params = json_decode($argv[2], true);
		foreach ($replace_params as $key => $value) {
			if (isset($GLOBALS[$key])) {
				$GLOBALS[$key] = array_replace_recursive($GLOBALS[$key], $value);
			} else {
				$GLOBALS[$key] = $value;
			}
		}
	}
	require_once './index.php';
})($argv);
