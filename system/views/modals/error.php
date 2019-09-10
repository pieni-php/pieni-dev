<div class="modal fade" tabindex="-1" id="error_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="alert alert-warning alert-dismissible m-0">
        <strong class="error_type"></strong>
<?php if ($this->config['debug']): ?>
        <br>
        <small>debug message:</small>
        <p class="debug m-0"></p>
        <small>To hide debug message, set <i>debug</i> to <i>false</i> in <i>./config.php.</i></small>
<?php endif; ?>
        <button class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
    </div>
  </div>
</div>
