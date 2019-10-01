<div class="container">
  <h1><?php $this->h($this->target['target']); ?></h1>
  <h2>Dynamic contents from the model</h2>
  <pre id="result">
  </pre>
  <hr>
  <div id="children">
<?php foreach ($this->target['children'] as $child): ?>
    <pre id="<?php echo $child; ?>">
    </pre>
<?php endforeach; ?>
  </div>
</div>
