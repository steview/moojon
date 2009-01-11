<?php
class inner_moojon_join extends moojon_join
{
	public function render($query_builder)
	{
		$query_builder->add_obj($this->foreign_table);
		$query_builder->add_where($this->local_table.'.'.$this->local_key.' = '.$this->foreign_table.'.'.$this->foreign_key);
		return '';
	}
}

class inner_join extends inner_moojon_join {}
class equi_moojon_join extends inner_moojon_join {}
class equi_join extends inner_moojon_join {}
?>