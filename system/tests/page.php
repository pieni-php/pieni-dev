<?php
return [
	'_server' => [
		'PATH_INFO' => '/',
	],
	'expected' => [
		'response' => <<<EOT
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Welcome to pieni</title>
  </head>
  <body>
<h1>Welcome to pieni!</h1>
<p>The page you are looking at is being generated dynamically by pieni.</p>
<p>If you would like to customize this page, copy <i>views/welcome/index.php</i> from <i>./system/</i> to <i>./application/</i>.</p>
<p>If you are exploring pieni for the very first time, you should start by reading the <a target="_blank" href="https://pieni.org/">User Guide</a>.</p>
  </body>
</html>

EOT
		,
	],
];
