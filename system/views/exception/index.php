  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" onclick="history.back();">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <h3 class="text-center"><?php $this->h($data['error_message']); ?></h3>
<?php if ($this->config['debug']): ?>
        <div class="card debug">
          <div class="card-body">
          <p class="card-text"><code><?php $this->h($data['debug']['exception_message']); ?></code> in <code><?php $this->h($data['debug']['exception_file']); ?></code> on line <code><?php $this->h($data['debug']['exception_line']); ?></code></p>
          <div><?php echo $data['debug']['debug_message']; ?></div>
          <p class="card-text small text-muted text-right">To hide this message, set <code>debug</code> to <code>false</code> in <code>config.php</code>.</p>
          </div>
        </div>
<?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="history.back();">Back</button>
      </div>
    </div>
  </div>
