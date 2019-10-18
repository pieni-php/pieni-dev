<div class="container">
  <h1><?php $this->h($target['target']); ?></h1>
  <button class="show_add_modal btn btn-primary">add</button>
  <table class="table" style="width:0; white-space:nowrap;">
    <tr>
<?php foreach ($target['action_column_names'][$request['action']] as $column_name): ?>
      <th><?php $this->h($column_name); ?></th>
<?php endforeach; ?>
      <th>actions</th>
    </tr>
    <tr class="row_template d-none">
<?php foreach ($target['action_column_names'][$request['action']] as $column_name): ?>
      <td name="<?php $this->h($column_name); ?>"></td>
<?php endforeach; ?>
      <td>
        <button class="show_edit_modal btn btn-primary">edit</button>
        <button class="show_delete_modal btn btn-primary">delete</button>
      </td>
    </tr>
  </table>
</div>
<?php $this->load_view('add', [], ['action' => 'add']); ?>
<?php $this->load_view('edit', [], ['action' => 'edit']); ?>
<?php $this->load_view('delete', [], ['action' => 'delete']); ?>
