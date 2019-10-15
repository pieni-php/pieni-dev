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
			'FROM `'.$this->target['target'].'`'."\n".
			$this->get_join_tables().
			'WHERE '.$this->target['columns'][$this->target['target'].'_id']['expr'].' = :id',
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
			'FROM `'.$this->target['target'].'`'."\n".
			$this->get_join_tables().
			'WHERE '.$parent_target['columns'][$parent_target['target'].'_id']['expr'].' = :parent_id',
			[
				'parent_id' => [
					'value' => $parent_id,
					'data_type' => PDO::PARAM_STR,
				],
			]
		);
	}

	public function exec_edit($id)
	{
		return $this->pbe(
			'UPDATE `'.$this->target['target'].'`'."\n".
			'SET '.$this->get_set_clause()."\n".
			'WHERE '.$this->target['columns'][$this->target['target'].'_id']['expr'].' = :id',
			array_merge([
				'id' => [
					'value' => $id,
					'data_type' => $this->target['columns'][$this->target['target'].'_id']['data_type'],
				],
			], $this->get_bind_assocs($_POST))
		);
	}
}
