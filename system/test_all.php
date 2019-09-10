<?php
exec('ls -t '.$argv[1].'/*.php', $output);
foreach ($output as $test_file) {
	$test = require_once $test_file;
	echo $test_file.': ';
	ob_start();
	passthru('php ./system/test.php '.$test_file, $return_var);
	$response = ob_get_clean();
	if ($response !== $test['expected']['response']) {
		echo 'failed'."\n";
		echo 'actual'."\n";
		echo $response."\n";
		echo 'expected'."\n";
		echo $test['expected']['response']."\n";
		exit(1);
	}
	echo 'succeeded'."\n";
}
exit(0);
