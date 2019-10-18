<form class="modal fade" id="<?php $this->h($target['target']); ?>_delete" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">delete</h5>
      </div>
      <div class="modal-body">
<?php foreach ($target['action_column_names'][$request['action']] as $column_name): ?>
        <div class="form-group">
          <label class="col-form-label"><?php $this->h($column_name); ?>:</label>
          <input class="form-control" name="<?php $this->h($column_name); ?>" readonly>
        </div>
<?php endforeach; ?>
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger">delete</button>
      </div>
    </div>
  </div>
</form>
