<?php
return [
	'_server' => [
		'PATH_INFO' => '/asdf',
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
<h1>Not Found</h1>
    <small>debug:</small>
    <p>Invalid argc</p>
    <small>To hide this message, set <i>debug</i> to <i>false</i> in <i>./config.php.</i></small>
  </body>
</html>

EOT
		,
	],
];
