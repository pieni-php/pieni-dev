<?php
$_SERVER = [
	'PATH_INFO' => $argv[1],
	'REQUEST_SCHEME' => 'https',
	'SERVER_NAME' => 'localhost',
	'SCRIPT_NAME' => 'index.php',
];
require_once './index.php';
