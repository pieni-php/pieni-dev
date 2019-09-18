We accepted a reset password request

Please reset password from the following URL within <?php echo $this->target['token_expire_minutes']; ?> minutes.
<?php $this->href($this->request['target'].'/reset_password/'.$data['id'].'/'.$data['token'], ['actor' => 'g']); ?>

<?php $this->load_mail('signature', $data); ?>
