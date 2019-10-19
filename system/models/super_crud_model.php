<?php
class super_crud_model extends model {
	public function index()
	{
		return $this->target;
	}

	public function view($id)
	{
		return $id;
	}
}
