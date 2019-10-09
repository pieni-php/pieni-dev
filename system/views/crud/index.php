<div class="container">
  <h1><?php $this->h($target['target']); ?></h1>
  <table class="table" style="width:0; white-space:nowrap;">
    <tr>
<?php foreach ($target['action_column_names'][$request['action']] as $column_name): ?>
      <th><?php $this->h($column_name); ?></th>
<?php endforeach; ?>
    </tr>
    <tr id="row_template" class="d-none">
<?php foreach ($target['action_column_names'][$request['action']] as $column_name): ?>
      <td name="<?php $this->h($column_name); ?>"></td>
<?php endforeach; ?>
    </tr>
  </table>
  <pre id="result">
  </pre>
</div>
