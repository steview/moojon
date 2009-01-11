<?php
abstract class moojon_base_number_column extends moojon_base_column
{
	private $unsigned;
	private $zerofill;
	
	protected function __construct($primary_key, $name, $length, $decimals, $null, $default_value, $comment, $unsigned, $zerofill)
	{
		parent::__construct($primary_key, $name, $length, $decimals, $null, $default_value, $comment);
		$this->unsigned = $unsigned;
		$this->zerofill = $zerofill;
	}
	
	final public function get_unsigned()
	{
		return $this->unsigned;
	}
	
	final public function get_zerofill()
	{
		return $this->zerofill;
	}
	
	protected function validate($value) {return true;}
}
?>