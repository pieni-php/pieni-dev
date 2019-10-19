<?php
class super_crud_model extends model {
	public function index()
	{
		return $this->rows(
			'SELECT * FROM `'.$this->target['table'].'`'
		);
	}

	public function child_of(...$parent_ids)
	{
		return $this->rows(
			'SELECT * FROM `'.$this->target['table'].'` WHERE '.$this->target['columns']['parent_ids'][0]['expr'].' = :parent_id',
			[
				'parent_id' => [
					'value' => $parent_ids[0],
					'data_type' => $this->target['columns']['parent_ids'][0]['data_type'],
				],
			]
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
