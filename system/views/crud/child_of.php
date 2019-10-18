  <table class="table" style="width:0; white-space:nowrap;">
    <tr>
<?php foreach ($target['as_child_of'][$request['params'][0]]['action_column_names'][$request['action']] as $column_name): ?>
      <th><?php $this->h($column_name); ?></th>
<?php endforeach; ?>
    </tr>
    <tr class="row_element d-none">
<?php foreach ($target['as_child_of'][$request['params'][0]]['action_column_names'][$request['action']] as $column_name): ?>
      <td name="<?php $this->h($column_name); ?>"></td>
<?php endforeach; ?>
    </tr>
  </table>
