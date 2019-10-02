<?php
class super_crud_model extends model {
	public function index()
	{
		return $this->rows(
			'SELECT '.$this->get_select_clause()."\n".
			'FROM `'.$this->target['target'].'`'.
			$this->get_join_tables()
		);
	}

	public function view($id)
	{
		return $this->row(
			'SELECT '.$this->get_select_clause()."\n".
			'FROM `'.$this->target['target'].'` WHERE '.$this->target['id_expr'].' = :id',
			[
				'id' => [
					'value' => $id,
					'data_type' => PDO::PARAM_STR,
				],
			]
		);
	}

	public function child_of($parent, $parent_id)
	{
		$parent_target = $this->load_target($parent);
		return $this->rows(
			'SELECT '.$this->get_select_clause()."\n".
			'FROM `'.$this->target['target'].'` WHERE '.$parent_target['id_expr'].' = :parent_id',
			[
				'parent_id' => [
					'value' => $parent_id,
					'data_type' => PDO::PARAM_STR,
				],
			]
		);
	}
}
