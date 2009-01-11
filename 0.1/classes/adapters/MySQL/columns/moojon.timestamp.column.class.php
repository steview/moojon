<?php
class moojon_timestamp_column extends moojon_base_column
{
	private $on_update_current_timestamp;
	
	protected function __construct($primary_key, $name, $length, $decimals, $null, $default_value, $comment, $on_update_current_timestamp)
	{
		parent::__construct($primary_key, $name, $length, $decimals, $null, $default_value, $comment);
		$this->on_update_current_timestamp = $on_update_current_timestamp;
	}
	
	final public function get_on_update_current_timestamp()
	{
		return $this->on_update_current_timestamp;
	}
	
	protected function validate($value) {return true;}
}

class timestamp_column extends moojon_timestamp_column {}
?>