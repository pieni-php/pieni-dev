<?php
class welcome_model extends model {
	public function index()
	{
		return $this->row('SELECT 42 AS "Answer to the Ultimate Question of Life, The Universe, and Everything"');
	}
}
