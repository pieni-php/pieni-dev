Welcome to pieni!

Please register from the following URL within <?php echo $this->target['token_expire_minutes']; ?> minutes.
<?php $this->href($this->request['target'].'/register/'.$data['id'].'/'.$data['token'], ['actor' => 'g']); ?>

<?php $this->load_mail('signature', $data); ?>
