<?php $super_target_controller_path = core::fallback(
	[
		['controllers'],
		isset($this->target['fallback']) ? ['super_'.$this->request['target'].'_controller.js', 'super_'.$this->target['fallback'].'_controller.js'] :  ['super_'.$this->request['target'].'_controller.js'],
	],
	function ($path) {
		return $path;
	},
	function ($file) {
		return false;
	}
); ?>
<?php $target_controller_path = core::fallback(
	[
		['controllers'],
		isset($this->target['fallback']) ? [$this->request['target'].'_controller.js', $this->target['fallback'].'_controller.js'] :  [$this->request['target'].'_controller.js'],
	],
	function ($path) {
		return $path;
	},
	function ($file) {
		return false;
	}
); ?>
<?php if ($super_target_controller_path !== false || $target_controller_path !== false): ?>
<?php $super_controller_path = core::fallback(
	[
		['classes'],
		['super_controller.js'],
	],
	function ($path) {
		return $path;
	},
	function ($file) {
		return false;
	}
); ?>
<?php $controller_path = core::fallback(
	[
		['classes'],
		['controller.js'],
	],
	function ($path) {
		return $path;
	},
	function ($file) {
		return false;
	}
); ?>
<script src="<?php $this->href($super_controller_path, ['actor' => $this->config['actors'][0]]); ?>"></script>
<?php if ($controller_path !== false): ?>
<script src="<?php $this->href($controller_path, ['actor' => $this->config['actors'][0]]); ?>"></script>
<?php else: ?>
<script>const controller = super_controller;</script>
<?php endif; ?>
<?php if ($super_target_controller_path !== false): ?>
<script src="<?php $this->href($super_target_controller_path, ['actor' => $this->config['actors'][0]]); ?>"></script>
<?php endif; ?>
<?php if ($target_controller_path !== false): ?>
<script src="<?php $this->href($target_controller_path, ['actor' => $this->config['actors'][0]]); ?>"></script>
<script>new <?php $this->h(pathinfo($target_controller_path)['filename']); ?>(<?php echo json_encode($this->config, JSON_UNESCAPED_UNICODE); ?>, <?php echo json_encode($this->request, JSON_UNESCAPED_UNICODE); ?>, <?php echo json_encode($this->target, JSON_UNESCAPED_UNICODE); ?>);</script>
<?php else: ?>
<script>new <?php $this->h(pathinfo($super_target_controller_path)['filename']); ?>(<?php echo json_encode($this->config, JSON_UNESCAPED_UNICODE); ?>, <?php echo json_encode($this->request, JSON_UNESCAPED_UNICODE); ?>, <?php echo json_encode($this->target, JSON_UNESCAPED_UNICODE); ?>);</script>
<?php endif; ?>
<?php endif; ?>
