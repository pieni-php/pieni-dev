<div class="container">
  <h2>Index of <?php $this->h($this->request['target']); ?></h2>
          <div class="text-right">
<?php foreach($this->config['actions']['page'][$this->request['actor']][$this->request['target']] as $action_name => $action): ?>
<?php if ($action['argc'] !== 0 || $action_name === 'index') continue; ?>
          <a href="<?php $this->href($this->request['target'].'/'.$action_name); ?>" class="btn btn-primary"><?php $this->h($action_name); ?></a>
<?php endforeach; ?>
          </div>
  <div class="row">
    <div class="col-4 d-none">
      <div class="card mb-5">
        <div class="card-body">
          <h5 name="_name" class="card-title"></h5>
          <table class="table">
<?php foreach ($this->target['actions'][$this->request['action']]['columns'] as $column_name): ?>
            <tr>
              <th class="text-right"><?php $this->h($column_name); ?></th>
              <td name="<?php $this->h($column_name); ?>"></td>
            </tr>
<?php endforeach; ?>
          </table>
          <div class="text-right">
<?php foreach($this->config['actions']['page'][$this->request['actor']][$this->request['target']] as $action_name => $action): ?>
<?php if ($action['argc'] !== 1) continue; ?>
          <a href="<?php $this->href($this->request['target'].'/'.$action_name); ?>" class="btn btn-primary"><?php $this->h($action_name); ?></a>
<?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
