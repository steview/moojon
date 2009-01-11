<?php
class cross_moojon_join extends moojon_join
{
	public function render($query_builder)
	{
		$query_builder->add_obj($this->foreign_table);
		return '';
	}
}

class cross_join extends cross_moojon_join {}
?>