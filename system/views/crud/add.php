<form class="modal fade" id="<?php $this->h($target['target']); ?>_add" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">add</h5>
      </div>
      <div class="modal-body">
<?php foreach ($target['action_column_names']['exec_'.$request['action']] as $column_name): ?>
        <div class="form-group">
          <label class="col-form-label"><?php $this->h($column_name); ?>:</label>
          <input class="form-control" name="<?php $this->h($column_name); ?>">
        </div>
<?php endforeach; ?>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">save</button>
      </div>
    </div>
  </div>
</form>
