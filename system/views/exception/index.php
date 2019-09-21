    <div class="container jumbotron text-center">
      <h3 class="text-center"><?php $this->h($data['error_message']); ?></h3>
<?php if ($this->config['debug']): ?>
      <div class="card">
        <div class="card-body">
          <p class="card-text"><code><?php $this->h($data['debug']['exception_message']); ?></code> in <code><?php $this->h($data['debug']['exception_file']); ?></code> on line <code><?php $this->h($data['debug']['exception_line']); ?></code></p>
          <p class="card-text"><?php $this->h($data['debug']['debug_message']); ?></p>
          <p class="card-text small text-muted text-right">To hide this message, set <code>debug</code> to <code>false</code> in <code>config.php</code>.</p>
        </div>
      </div>
<?php endif; ?>
      <button class="btn btn-primary btn-lg mt-3" onclick="history.back();">Back</a>
    </div>
