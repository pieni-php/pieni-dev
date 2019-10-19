<?php
class super_crud_model extends model {
	public function index()
	{
		return $this->rows(
			'SELECT * FROM `'.$this->target['table'].'`'
		);
	}

	public function view($id)
	{
		return $this->row(
			'SELECT * FROM `'.$this->target['table'].'` WHERE '.$this->target['columns'][$this->target['table'].'_id']['expr'].' = :id',
			[
				'id' => [
					'value' => $id,
					'data_type' => PDO::PARAM_STR,
				],
			]
		);
	}
}
