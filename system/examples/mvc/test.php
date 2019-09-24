<?php
function rows($dbh, $statement)
{
	return ['rows' => $dbh->query($statement)->fetchAll(PDO::FETCH_ASSOC)];
}

function row($dbh, $statement)
{
	return ['row' => $dbh->query($statement)->fetch(PDO::FETCH_ASSOC)];
}

function exec_request($path_info)
{
	ob_start();
	return json_decode(shell_exec('php '.__DIR__.'/test_driver.php '.$path_info), true);
}

function succeeded()
{
	$caller = debug_backtrace()[1];
	echo $caller['args'][0].' '."\033[32m".'succeeded'."\033[m\n";
}

function failed()
{
	$caller = debug_backtrace()[1];
	echo $caller['args'][0].' '."\033[31m".'failed'."\033[m".' in '.preg_replace('#^'.getcwd().'#', '.', $caller['file']).' on line '.$caller['line']."\n";
	echo 'actual_values:'."\n";;
	var_export($caller['args'][1]);
	echo "\n";
	echo 'expected_values:'."\n";;
	var_export($caller['args'][2]);
	echo "\n";
	exit(1);
}

function equals_to($label, $actual_values, $expected_values)
{
	$actual_values === $expected_values ? succeeded() : failed();
}

function contains($label, $actual_values, $expected_values)
{
	$actual_values === array_replace_recursive($actual_values, $expected_values) ? succeeded() : failed();}

function not_contains($label, $actual_values, $expected_values)
{
	$actual_values !== array_replace_recursive($actual_values, $expected_values) ? succeeded() : failed();
}

$config = require_once __DIR__.'/config.php';
if (file_exists('./development_config.php')) {
	$config = array_replace_recursive($config, require './development_config.php');
}
$dbh = new PDO(
	$config['pdo']['dsn'],
	$config['pdo']['username'],
	$config['pdo']['password'],
	[
		PDO::ATTR_EMULATE_PREPARES => false,
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	]
);
foreach (explode("\n", trim(shell_exec('find '.__DIR__.'/tests -type f -path \'*\.php\' -print0 | xargs -0 ls -t'))) as $test_file) {
	echo $test_file."\n";
	require_once $test_file;
	echo "\n";
}
