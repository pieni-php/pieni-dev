<div class="container">
  <form class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Email sending example</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>To</label>
          <input type="email" name="to" class="form-control" value="root@localhost">
        </div>
        <div class="form-group">
          <label>Name</label>
          <input type="text" name="name" class="form-control" value="John Smith">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-secondary">Send mail</button>
      </div>
    </div>
  </form>
  <hr>
  <h2>Return values from model::send_mail()</h2>
  <pre id="result">
  </pre>
</div>
