<?php
class welcome_model extends model {
	public function index()
	{
		return $this->send_mail('index', ['to' => $_POST['to'], 'name' => $_POST['name']]);
	}
}
