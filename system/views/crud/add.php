<form class="container">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Adding <?php $this->h($this->request['target']); ?></h5>
      </div>
      <div class="modal-body">
<?php foreach ($this->target['actions'][$this->request['action'].'_affect']['columns'] as $column_name): ?>
        <div class="form-group">
          <label class="col-form-label"><?php $this->h($column_name); ?></label>
          <input class="form-control" name="<?php $this->h($column_name); ?>">
        </div>
<?php endforeach; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" onclick="history.back();">Back</button>
        <button class="btn btn-primary">Add</button>
      </div>
    </div>
  </div>
</form>
