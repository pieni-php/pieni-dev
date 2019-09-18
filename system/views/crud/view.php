<form class="container">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Viewing <span name="_name"></span></h5>
      </div>
      <div class="modal-body">
<?php foreach ($this->target['actions'][$this->request['action']]['columns'] as $column_name): ?>
        <div class="form-group">
          <label class="col-form-label"><?php $this->h($column_name); ?></label>
          <input class="form-control-plaintext" name="<?php $this->h($column_name); ?>" readonly>
        </div>
<?php endforeach; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" onclick="history.back();">Back</button>
      </div>
    </div>
  </div>
</form>
<?php if (isset($this->target['children'])): ?>
<div class="container">
  <ul class="nav nav-tabs">
<?php foreach ($this->target['children'] as $child_name => $child): ?>
    <li class="nav-item">
      <a class="nav-link<?php if ($child_name === array_keys($this->target['children'])[0]): ?> active<?php endif; ?>" data-toggle="tab" href="#tab_<?php $this->h($child_name); ?>"><?php $this->h($child_name); ?></a>
    </li>
<?php endforeach; ?>
  </ul>
  <div class="tab-content">
<?php foreach ($this->target['children'] as $child_name => $child): ?>
    <div class="tab-pane fade<?php if ($child_name === array_keys($this->target['children'])[0]): ?> show active<?php endif; ?>" id="tab_<?php $this->h($child_name); ?>">
<?php $this->load_view('child_of', $data, ['target' => $child_name]); ?>
    </div>
<?php endforeach; ?>
  </div>
</div>
<?php endif; ?>
