<div class="modal fade" id="exception_modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h3 class="text-center error_message"></h3>
<?php if ($this->config['debug']): ?>
        <div class="card debug">
          <div class="card-body">
            <p class="card-text"><code class="exception_message"></code> in <code class="exception_file"></code> on line <code class="exception_line"></code></p>
            <div class="debug_message"></div>
            <p class="card-text small text-muted text-right">To hide this message, set <code>debug</code> to <code>false</code> in <code>config.php</code>.</p>
          </div>
        </div>
<?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
