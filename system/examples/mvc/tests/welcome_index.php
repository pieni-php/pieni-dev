<?php
equals_to(
	'request /api',
	exec_request('/api'),
	[
		'1 + 1' => 2,
	]
);
