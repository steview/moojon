<?php
class left_moojon_join extends moojon_join
{
	public function render($query_builder)
	{
		$query_builder->add_data($this->foreign_table.'.*');
		return 'LEFT JOIN '.$this->local_table.' ON '.$this->foreign_table.'.'.$this->foreign_key.' = '.$this->local_table.'.'.$this->local_key;
	}
}

class left_join extends left_moojon_join {}
?>