<?php
abstract class moojon_base_enum_column extends moojon_base_column
{
	private $enum_value;
	
	protected function __construct($primary_key, $name, $length, $decimals, $null, $default_value, $comment, $enum_value)
	{
		parent::__construct($primary_key, $name, $length, $decimals, $null, $default_value, $comment);
		$this->enum_value = $enum_value;
	}
	
	final public function get_enum_value()
	{
		return $this->enum_value;
	}
	
	protected function validate($value) {return true;}
}
?>