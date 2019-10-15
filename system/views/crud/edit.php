<form class="modal fade" id="<?php $this->h($target['target']); ?>_edit">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Join</h5>
      </div>
      <div class="modal-body">
<?php foreach ($target['action_column_names'][$request['action']] as $column_name): ?>
        <div class="form-group">
          <label class="col-form-label"><?php $this->h($column_name); ?>:</label>
          <input class="form-control" name="category_name">
        </div>
<?php endforeach; ?>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Receive email to register</button>
      </div>
    </div>
  </div>
</form>
