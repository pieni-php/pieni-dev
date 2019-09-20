<!doctype html>
<html lang="<?php $this->h($this->request['language']); ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>pieni - PHP Framework</title>
    <script src="<?php $this->load_referable('jquery.min.js'); ?>"></script>
    <script src="<?php $this->load_referable('bootstrap.min.js'); ?>"></script>
    <link rel="stylesheet" href="<?php $this->load_referable('bootstrap.min.css'); ?>">
  </head>
  <body>
    <div class="container jumbotron">
      <h1 class="text-center"><?php $this->h($data['error_message']); ?></h1>
<div class="card">
  <div class="card-body">
    <p class="card-text"><code><?php $this->h($data['debug']['exception_message']); ?></code> in <code><?php $this->h(preg_replace('#^'.getcwd().'#', '.', $data['debug']['exception_file'])); ?></code> on line <code><?php $this->h($data['debug']['exception_line']); ?></code></p>
    <p class="card-text"><?php $this->h($data['debug']['debug_message']); ?></p>
    <p class="card-text small text-muted text-right">To hide this message, set <code>debug</code> to <code>false</code> in <code>config.php</code>.</p>
  </div>
</div>
    </div>
  </body>
</html>
