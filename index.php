<?php
file_exists('./application/classes/super_core.php') ?
	require_once './application/classes/super_core.php' :
	require_once './system/classes/super_core.php'
;
file_exists('./application/classes/core.php') ?
	require_once './application/classes/core.php' :
	file_exists('./application/classes/core.php') ?
		require_once './system/classes/core.php' :
		class_alias('super_core', 'core')
;
(new core($_SERVER))->exec_request();
