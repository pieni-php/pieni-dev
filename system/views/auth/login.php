<form class="container">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Login</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="col-form-label">Email:</label>
          <input class="form-control" name="email">
        </div>
        <div class="form-group">
          <label class="col-form-label">Password:</label>
          <input class="form-control" name="password" type="password">
        </div>
      </div>
      <div class="modal-footer">
        <small><a href="<?php $this->href('auth_'.$this->target['actor'].'/forgot_password'); ?>">Forgot password?</a></small>
        <button class="btn btn-primary">Login</button>
      </div>
    </div>
  </div>
</form>
