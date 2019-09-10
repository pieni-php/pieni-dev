<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="<?php $this->href(''); ?>">CRUD demo</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-collapse">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item<?php if ($this->request['target'] === 'category'): ?> active<?php endif; ?>"><a class="nav-link" href="<?php $this->href('category'); ?>">Category</a></li>
      <li class="nav-item<?php if ($this->request['target'] === 'item'): ?> active<?php endif; ?>"><a class="nav-link" href="<?php $this->href('item'); ?>">Item</a></li>
    </ul>
  </div>
</nav>
