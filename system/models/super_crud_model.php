<?php
class super_crud_model extends model {
	protected function get_id_conditions($ids)
	{
		$array = [];
		foreach ($this->target['id_column_names'] as $column_name) {
			$column = $this->target['columns'][$column_name];
			$array[] = '`'.$column_name.'` = :'.$column_name;
		}
		return implode(' AND ', $array);
	}

	protected function get_id_assoc($ids)
	{
		$assoc = [];
		foreach ($this->target['id_column_names'] as $i => $column_name) {
			$column = $this->target['columns'][$column_name];
			$assoc[$column_name] = [
				'data_type' => $column['data_type'],
				'value' => $ids[$i],
			];
		}
		return $assoc;
	}

	public function index()
	{
		return $this->rows(
			'SELECT * FROM '.$this->target['from_expr']
		);
	}

	public function view(...$ids)
	{
		return $this->row(
			'SELECT * FROM '.$this->target['from_expr'].' WHERE '.$this->get_id_conditions($ids),
			$this->get_id_assoc($ids)
		);
	}
}
