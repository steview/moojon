<?php
abstract class moojon_base_column extends moojon_base
{
	private $primary_key;
	private $name;
	private $length;
	private $decimals;
	private $null;
	private $default_value;
	private $comment;
	
	private $value;
	
	protected function __construct($primary_key, $name, $length, $decimals, $null, $default_value, $comment)
	{
		$this->primary_key = $primary_key;
		$this->name = $name;
		$this->length = $length;
		$this->decimals = $decimals;
		$this->null = $null;
		$this->default_value = $default_value;
		$this->comment = $comment;
	}
		
	final public function set_value($value)
	{
		if ($this->validate($value))
		{
			$this->value = $value;
			return true;
		}
		else
		{
			if ($this->comment != null)
			{
				return $this->comment;
			}
			else
			{
				return false;
			}
		}
	}
	
	final public function get_name()
	{
		return $this->name;
	}
	
	final public function get_null()
	{
		return $this->null;
	}
	
	final public function get_comment()
	{
		return $this->comment;
	}
	
	final public function get_primary_key()
	{
		return $this->primary_key;
	}
		
	final public function get_value()
	{
		return $this->value;
	}
	
	abstract protected function validate($value);
}
?>