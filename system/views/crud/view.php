<div class="container">
  <h1 name="name"></h1>
  <table class="table" style="width:0; white-space:nowrap;">
<?php foreach ($target['columns'] as $column_name => $column): ?>
    <tr>
      <th><?php $this->h($column_name); ?></th>
      <td name="<?php $this->h($column_name); ?>"></td>
    </tr>
<?php endforeach; ?>
  </table>
  <pre id="result">
  </pre>
  <hr>
<?php if (isset($target['child_names'])): ?>
  <ul class="nav nav-tabs">
    <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#home">Home</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#profile">Profile</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#contact">Contact</a>
    </li>
<?php foreach ($target['child_names'] as $child_name): ?>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#<?php echo $child_name; ?>"><?php echo $child_name; ?></a>
    </li>
<?php endforeach; ?>
  </ul>
  <div class="tab-content">
    <div class="tab-pane fade show active" id="home">...1</div>
    <div class="tab-pane fade" id="profile">...2</div>
    <div class="tab-pane fade" id="contact">...3</div>
<?php foreach ($target['children'] as $child_name => $child): ?>
    <div class="tab-pane fade" id="<?php echo $child_name; ?>">
<?php $this->load_view('child_of', [], ['target' => $child_name], $child); ?>
    </div>
<?php endforeach; ?>
  </div>
<?php endif; ?>
  <hr>
</div>
