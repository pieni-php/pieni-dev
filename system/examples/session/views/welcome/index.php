<?php $_SESSION['count'] = isset($_SESSION['count']) ? $_SESSION['count'] + 1 : 0; ?>
<div class="container">
  <h1>Session is stored in the database</h1>
  <div class="alert alert-info">If reloaded within <?php $this->h(number_format(ini_get('session.gc_maxlifetime'))); ?> seconds, the <code>$_SESSION['count']</code> will increment.</div>
  <h2>$_SESSION['count']</h2>
  <pre>
<?php var_dump($_SESSION['count']); ?>
  </pre>
  <h2>session_id()</h2>
  <pre>
<?php var_dump(session_id()); ?>
  </pre>
  <h2>ini_get('session.gc_maxlifetime')</h2>
  <pre>
<?php var_dump(ini_get('session.gc_maxlifetime')); ?>
  </pre>
  <h2>session_get_cookie_params()</h2>
  <pre>
<?php var_export(session_get_cookie_params()); ?>
  </pre>
</div>
