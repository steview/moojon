<?php
abstract class moojon_query_utilities extends moojon_base
{
	final static protected function find_builder($args)
	{
		$builder = null;
		foreach ($args as $arg)
		{
			if (get_class($arg) == 'moojon_query_builder')
			{
				$builder = $arg;
				break;
			}
		}
		return $builder;
	}
	
	final static protected function resolve($value, $builder, $property, $string = null)
	{
		if (get_class($value) == 'moojon_query_builder')
		{
			$value = '';
		}
		if (empty($value))
		{
			$value = $builder->$property;
		}
		if (!empty($value))
		{
			if (!empty($string))
			{
				return sprintf($string, trim($value));
			}
			else
			{
				if (gettype($value) == 'string')
				{
					return trim($value);
				}
				return $value;
			}
		}
		return '';
	}
}
?>