<div class="container">
  <h1 id="<?php echo $target['target']; ?>_name"></h1>
  <table class="table" id="<?php echo $target['target']; ?>" style="width:0; white-space:nowrap;">
<?php foreach ($target['action_column_names'][$request['action']] as $column_name): ?>
    <tr>
      <th><?php $this->h($column_name); ?></th>
      <td name="<?php $this->h($column_name); ?>"></td>
    </tr>
<?php endforeach; ?>
  </table>
  <hr>
<?php if (isset($target['child_names'])): ?>
  <ul class="nav nav-tabs">
<?php foreach ($target['child_names'] as $i => $child_name): ?>
    <li class="nav-item">
      <a class="nav-link<?php if ($i === 0): ?> active<?php endif; ?>" data-toggle="tab" href="#<?php echo $child_name; ?>"><?php echo $child_name; ?></a>
    </li>
<?php endforeach; ?>
  </ul>
  <div class="tab-content">
<?php foreach ($target['children'] as $child_name => $child): ?>
    <div class="tab-pane fade<?php if ($i === 0): ?> show active<?php endif; ?>" id="<?php echo $child_name; ?>">
<?php $this->load_view('child_of', [], ['target' => $child_name, 'action' => 'child_of', 'params' => [$request['target'], $request['params'][0]]], $child); ?>
    </div>
<?php endforeach; ?>
  </div>
<?php endif; ?>
  <hr>
</div>
<?php $this->load_view('edit'); ?>
