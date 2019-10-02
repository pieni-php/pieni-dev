<div class="container">
  <h1><?php $this->h($target['target']); ?></h1>
  <table class="table" style="width:0; white-space:nowrap;">
    <tr>
<?php foreach (array_merge(['name' => []], $target['columns']) as $column_name => $column): ?>
      <th><?php $this->h($column_name); ?></th>
<?php endforeach; ?>
    </tr>
    <tr id="row_template" class="d-none">
<?php foreach (array_merge(['name' => []], $target['columns']) as $column_name => $column): ?>
      <td name="<?php $this->h($column_name); ?>"></td>
<?php endforeach; ?>
    </tr>
  </table>
  <pre id="result">
  </pre>
</div>
