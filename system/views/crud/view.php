<div class="container">
  <h1 name="name"></h1>
  <table class="table" style="width:0; white-space:nowrap;">
<?php foreach ($target['columns'] as $column_name => $column): ?>
    <tr>
      <th><?php $this->h($column_name); ?></th>
      <td name="<?php $this->h($column_name); ?>"></td>
    </tr>
<?php endforeach; ?>
  </table>
  <pre id="result">
  </pre>
  <hr>
<?php if (isset($target['child_names'])): ?>
  <div id="children">
<?php foreach ($target['child_names'] as $child_name): ?>
    <pre id="<?php echo $child_name; ?>">
    </pre>
<?php endforeach; ?>
  </div>
<?php endif; ?>
<hr>
<pre>
<?php print_r($target); ?>
</pre>
</div>
