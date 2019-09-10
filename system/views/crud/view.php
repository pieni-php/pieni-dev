<form class="container">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Viewing <span name="_name"></span></h5>
      </div>
      <div class="modal-body">
<?php foreach ($this->target['actions'][$this->request['action']]['columns'] as $column_name): ?>
        <div class="form-group">
          <label class="col-form-label"><?php $this->h($column_name); ?></label>
          <input class="form-control-plaintext" name="<?php $this->h($column_name); ?>" readonly>
        </div>
<?php endforeach; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" onclick="history.back();">Back</button>
      </div>
    </div>
  </div>
</form>
