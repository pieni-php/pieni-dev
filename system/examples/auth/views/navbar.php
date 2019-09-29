<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="<?php $this->href(''); ?>">Auth demo</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-collapse">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse">
    <ul class="navbar-nav mr-auto">
<?php if (!isset($_SESSION['auth']['m'])): ?>
      <li class="nav-item<?php if ($this->request['target'] === 'auth_m' && $this->request['action'] === 'join'): ?> active<?php endif; ?>"><a class="nav-link" href="<?php $this->href('auth_m/join', ['actor' => 'g']); ?>">Join</a></li>
      <li class="nav-item<?php if ($this->request['target'] === 'auth_m' && $this->request['action'] === 'login'): ?> active<?php endif; ?>"><a class="nav-link" href="<?php $this->href('auth_m/login', ['actor' => 'g']); ?>">Login</a></li>
<?php endif; ?>
<?php if (isset($_SESSION['auth']['m'])): ?>
      <li class="nav-item<?php if ($this->request['target'] === 'auth_m' && $this->request['action'] === 'unregister'): ?> active<?php endif; ?>"><a class="nav-link" href="<?php $this->href('auth_m/unregister', ['actor' => 'm']); ?>">Unregister</a></li>
      <li class="nav-item<?php if ($this->request['target'] === 'auth_m' && $this->request['action'] === 'logout'): ?> active<?php endif; ?>"><a class="nav-link" href="<?php $this->href('auth_m/logout', ['actor' => 'm']); ?>">Logout</a></li>
      <li class="nav-item<?php if ($this->request['target'] === 'auth_m' && $this->request['action'] === 'change_email'): ?> active<?php endif; ?>"><a class="nav-link" href="<?php $this->href('auth_m/change_email', ['actor' => 'm']); ?>">Change Email</a></li>
      <li class="nav-item<?php if ($this->request['target'] === 'auth_m' && $this->request['action'] === 'change_password'): ?> active<?php endif; ?>"><a class="nav-link" href="<?php $this->href('auth_m/change_password', ['actor' => 'm']); ?>">Change Password</a></li>
<?php endif; ?>
    </ul>
  </div>
</nav>
