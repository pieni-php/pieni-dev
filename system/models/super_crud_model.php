<?php
class super_crud_model extends model {
	public function index()
	{
		return $this->rows('SELECT * FROM `'.$this->target['target'].'`');
	}
}
