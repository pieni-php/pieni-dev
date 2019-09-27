<?php
class welcome_model extends model {
	public function index()
	{
		return $this->row('SELECT 1 + 1');
	}
}
