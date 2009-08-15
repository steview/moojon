<?php
class right_moojon_join extends moojon_join
{
	public function render($query_builder)
	{
		return 'RIGHT JOIN '.$this->local_table.'.'.$this->local_key.' ON '.$this->foreign_table.'.'.$this->foreign_key;
	}
}

class right_join extends right_moojon_join {}
?>