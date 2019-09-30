<?php
equals_to(
	'request /api',
	exec_request('/api'),
	[
		'Answer to the Ultimate Question of Life, The Universe, and Everything' => 42,
	]
);
