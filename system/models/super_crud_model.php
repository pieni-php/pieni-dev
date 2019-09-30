<?php
class super_crud_model extends model {
	public function index()
	{
		return $this->rows('SELECT * FROM `'.$this->target['target'].'`');
	}

	public function view($id)
	{
		return $this->row('SELECT * FROM `'.$this->target['target'].'` WHERE '.$this->target['id_expr'].' = :id', [
			'id' => [
				'data_type' => PDO::PARAM_STR,
				'value' => $id,
			],
		]);
	}
}
