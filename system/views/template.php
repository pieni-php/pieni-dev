<!doctype html>
<html lang="<?php $this->h($this->request['language']); ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>pieni - PHP Framework</title>
    <script src="<?php $this->asset('jquery.min.js'); ?>"></script>
    <script src="<?php $this->asset('bootstrap.min.js'); ?>"></script>
    <link rel="stylesheet" href="<?php $this->asset('bootstrap.min.css'); ?>">
<?php $this->load_target_controller($this->request['target']); ?>
  </head>
  <body>
<?php $this->load_view('navbar', $data); ?>
<?php $this->load_view($this->request['action'], $data, ['target' => $this->request['target']]); ?>
<?php $this->load_view('modals/error', $data); ?>
<?php $this->load_view('modals/alert', $data); ?>
  </body>
</html>
