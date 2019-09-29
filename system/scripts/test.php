<?php
function restore($config, $file)
{
	echo shell_exec('mysql -u'.$config['pdo']['username'].' '.($config['pdo']['password'] !== '' ? ' -p'.$config['pdo']['password'] : '').' '.$GLOBALS['test_dbname'].' < '.$file);
}

function rows($dbh, $statement)
{
	return ['rows' => $dbh->query($statement)->fetchAll(PDO::FETCH_ASSOC)];
}

function row($dbh, $statement)
{
	return ['row' => $dbh->query($statement)->fetch(PDO::FETCH_ASSOC)];
}

function exec_request($path_info, $params = [])
{
	ob_start();
	return json_decode(shell_exec('php '.__DIR__.'/test_driver.php '.$GLOBALS['argv'][1].' '.$path_info.' \''.json_encode($params, JSON_UNESCAPED_UNICODE).'\''), true);
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

function password_hash_matches($label, $actual_values, $expected_values)
{
	password_verify($expected_values , $actual_values) ? succeeded() : failed();
}

$config = require_once './'.$argv[1].'/config.php';
if (isset($config['pdo'])) {
	$dsn = [];
	$head_body = explode(':', $config['pdo']['dsn']);
	if ($head_body[1] !== '') {
		foreach (explode(';', $head_body[1]) as $key_value) {
			[$key, $value] = explode('=', $key_value);
			$dsn[$key] = $value;
		}
	}
	if (isset($dsn['dbname'])) {
		$GLOBALS['test_dbname'] = $dsn['dbname'].'_test';
		shell_exec('mysql -u'.$config['pdo']['username'].($config['pdo']['password'] !== '' ? ' -p'.$config['pdo']['password'] : '').' -e \'DROP DATABASE IF EXISTS `'.$GLOBALS['test_dbname'].'`; CREATE DATABASE `'.$GLOBALS['test_dbname'].'`;\'');
	}
	$dbh = new PDO(
		preg_replace('/dbname=([^;]+)/', 'dbname=${1}_test', $config['pdo']['dsn']),
		$config['pdo']['username'],
		$config['pdo']['password'],
		[
			PDO::ATTR_EMULATE_PREPARES => false,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		]
	);
}
foreach (explode("\n", trim(shell_exec('find '.$argv[1].'/tests -type f -path \'*\.php\' -print0 | xargs -0 ls -t'))) as $test_file) {
	echo preg_replace('#^'.$argv[1].'/tests/#', '', $test_file)."\n";
	require_once $test_file;
	echo "\n";
}
