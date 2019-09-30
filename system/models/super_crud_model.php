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

	public function child_of($parent, $parent_id)
	{
		$parent_target = $this->load_target($parent);
		return $this->rows('SELECT * FROM `'.$this->target['target'].'` WHERE '.$parent_target['id_expr'].' = :parent_id', [
			'parent_id' => [
				'data_type' => PDO::PARAM_STR,
				'value' => $parent_id,
			],
		]);
	}
}
