<form class="container">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Register</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="col-form-label">Password:</label>
          <input class="form-control" name="password" type="password">
        </div>
        <div class="form-group">
          <label class="col-form-label">Name:</label>
          <input class="form-control" name="<?php $this->h($this->target['table']); ?>_name">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Register</button>
      </div>
    </div>
  </div>
</form>
