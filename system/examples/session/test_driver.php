<?php
$GLOBALS['test_params'] = json_decode($argv[2], true);
$_SERVER = [
	'PATH_INFO' => $argv[1],
	'REQUEST_SCHEME' => 'https',
	'SERVER_NAME' => 'localhost',
	'SCRIPT_NAME' => 'index.php',
];
$_COOKIE = [
	'PHPSESSID' => 'xxxxxxxx',
];
require_once './index.php';
