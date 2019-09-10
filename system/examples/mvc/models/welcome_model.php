<?php
class welcome_model extends model {
	public function index()
	{
		return $this->rows('SELECT * FROM `plugin`');
	}
}
