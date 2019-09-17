<?php
class super_crud_model extends model {
	public function __construct($config, $request, $target)
	{
		parent::__construct($config, $request, $target);
	}

	public function child_of($parent, $parent_id)
	{
		$parent_id_expr = core::get_target($parent, true)['id_expr'];
		return $this->rows('SELECT '.$this->get_select_clause().' FROM `'.$this->target['table'].'`'.$this->get_join_tables().' WHERE '.$parent_id_expr.' = :parent_id', [
			'parent_id' => [
				'value' => $parent_id,
				'data_type' => PDO::PARAM_STR,
			],
		]);
	}

	public function index()
	{
		return $this->rows('SELECT '.$this->get_select_clause().' FROM `'.$this->target['table'].'`'.$this->get_join_tables());
	}

	public function view($id)
	{
		$result = [];
		$result['row'] = $this->get_row($id);
		if (isset($this->children)) {
			$result['children'] = [];
			foreach ($this->children as $child_name => $child) {
//				$result['children'][$child_name] = $child->child_of($this->request['target'], $id);
			}
		}
		return $result;
	}

	public function add_affect()
	{
		$columns = $this->get_columns();
		$this->pbe(
			'INSERT INTO `'.$this->target['table'].'` SET '.$this->get_set_clause($columns),
			$this->get_bind_assocs($columns, $this->request['_post'])
		);
	}

	public function edit($id)
	{
		return ['row' => $this->get_row($id)];
	}

	public function edit_affect($id)
	{
		$columns = $this->get_columns();
		$this->pbe(
			'UPDATE `'.$this->target['table'].'` SET '.$this->get_set_clause($columns).' WHERE '.$this->target['id_expr'].' = :id',
			array_merge([
				'id' => [
					'value' => $id,
					'data_type' => PDO::PARAM_INT,
				],
			], $this->get_bind_assocs($columns, $this->request['_post']))
		);
	}

	public function delete($id)
	{
		return ['row' => $this->get_row($id)];
	}

	public function delete_affect($id)
	{
		$this->pbe(
			'DELETE FROM `'.$this->target['table'].'` WHERE '.$this->target['id_expr'].' = :id',
			[
				'id' => [
					'value' => $id,
					'data_type' => PDO::PARAM_INT,
				],
			]
		);
	}

	protected function get_row($id)
	{
		return $this->row('SELECT '.$this->get_select_clause().' FROM `'.$this->target['table'].'`'.$this->get_join_tables().' WHERE '.$this->target['id_expr'].' = :id', [
			'id' => [
				'value' => $id,
				'data_type' => PDO::PARAM_INT,
			],
		]);
	}

	protected function get_select_clause()
	{
		$array = [];
		$array[] = $this->target['id_expr'].' AS `_id`';
		$array[] = $this->target['name_expr'].' AS `_name`';
		foreach ($this->target['actions'][$this->request['action']]['columns'] as $column_name) {
			$array[] = isset($this->target['columns'][$column_name]['expr']) ? $this->target['columns'][$column_name]['expr'].' AS `'.$column_name.'`' : '`'.$column_name.'`';
		}
		return implode(', ', $array);
	}

	protected function get_join_tables()
	{
		if (!isset($this->target['join_tables'])) {
			return '';
		}
		$array = [];
		foreach ($this->target['join_tables'] as $join_table) {
			$array[] = $join_table;
		}
		return implode(' ', $array);
	}

	protected function get_columns()
	{
		$columns = [];
		foreach ($this->target['actions'][$this->request['action']]['columns'] as $column_name) {
			$columns[$column_name] = $this->target['columns'][$column_name];
		}
		return $columns;
	}
}
