<?php
class super_crud_model extends model {
	protected function get_parent_id_conditions()
	{
		return $this->target['columns'][$this->target['parent_id_column_names'][0]]['expr'].' = :'.$this->target['parent_id_column_names'][0];
	}

	protected function get_parent_id_params($parent_ids)
	{
		return [
			$this->target['parent_id_column_names'][0] => [
				'value' => $parent_ids[0],
				'data_type' => $this->target['columns'][$this->target['parent_id_column_names'][0]]['data_type'],
			],
		];
	}

	public function index()
	{
		return $this->rows(
			'SELECT * FROM `'.$this->target['table'].'`'
		);
	}

	public function child_of(...$parent_ids)
	{
		return $this->rows(
			'SELECT * FROM `'.$this->target['table'].'` WHERE '.$this->get_parent_id_conditions(),
			$this->get_parent_id_params($parent_ids)


		);
	}

	public function view($id)
	{
		return $this->row(
			'SELECT * FROM `'.$this->target['table'].'` WHERE '.$this->target['columns'][$this->target['table'].'_id']['expr'].' = :id',
			[
				'id' => [
					'value' => $id,
					'data_type' => $this->target['columns'][$this->target['table'].'_id']['data_type'],
				],
			]
		);
	}
}
