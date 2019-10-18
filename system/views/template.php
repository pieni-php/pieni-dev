<!doctype html>
<html lang="<?php $this->h($request['language']); ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="data:,">
    <title>pieni - PHP Framework</title>
    <script src="<?php $this->load_referable('jquery.min.js'); ?>"></script>
    <script src="<?php $this->load_referable('bootstrap.min.js'); ?>"></script>
    <link rel="stylesheet" href="<?php $this->load_referable('bootstrap.min.css'); ?>">
<?php $this->exec_action_method(); ?>
  </head>
  <body>
<?php $this->load_view('navbar', $data); ?>
<?php $this->load_view($request['action'], $data, $request); ?>
<?php if ($this->loaded_controller_class_names !== []) $this->load_view('exception_modal', $data); ?>
<?php if ($this->loaded_controller_class_names !== []) $this->load_view('alert_modal', $data); ?>
  </body>
</html>
