<div class="container">
  <h1 name="name"></h1>
  <table class="table">
<?php foreach ($this->target['columns'] as $column_name => $column): ?>
    <tr>
      <th><?php $this->h($column_name); ?></th>
      <td></td>
    </tr>
<?php endforeach; ?>
  </table>
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
