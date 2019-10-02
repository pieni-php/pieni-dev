<div class="container">
  <h1 name="name"></h1>
  <pre id="result">
  </pre>
  <hr>
  <div id="children">
<?php foreach ($this->target['child_names'] as $child_name): ?>
    <pre id="<?php echo $child_name; ?>">
    </pre>
<?php endforeach; ?>
  </div>

<hr>
<pre>
<?php print_r($this->target); ?>
</pre>

</div>
