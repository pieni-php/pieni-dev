<?php
$test = require_once $argv[1];
$_SERVER = array_merge([
	'REQUEST_SCHEME' => 'https',
	'SERVER_NAME' => 'localhost',
	'SCRIPT_NAME' => '/index.php',
], $test['_server']);
return require_once './index.php';
