<!DOCTYPE html>
<html lang="<?php $this->h($this->request['language']); ?>">
  <head>
    <meta charset="utf-8">
    <title>Welcome to pieni</title>
  </head>
  <body>
<?php $this->load_view('errors/'.$data['response_code'], $data); ?>
<?php if ($this->config['debug']): ?>
    <small>debug message:</small>
    <p><?php print_r($data['debug']); ?></p>
    <small>To hide debug message, set <i>debug</i> to <i>false</i> in <i>./config.php.</i></small>
<?php endif; ?>
  </body>
</html>
