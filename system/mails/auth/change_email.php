We accepted a change email request

Please change email from the following URL within <?php echo $this->target['token_expire_minutes']; ?> minutes.
<?php $this->href($this->request['target'].'/change_email_verify/'.$data['id'].'/'.$data['token'], ['actor' => 'g']); ?>
