<?php
class moojon_joins
{
	final protected function __construct() {}
	
	final static public function init($type, $foreign_table, $local_table = null, $foreign_key = null, $local_key = null)
	{
		$join = null;
		switch(strtolower($type))
		{
			case 'left':
				$join = new left_moojon_join($foreign_table, $local_table, $foreign_key, $local_key);
				break;
			case 'right':
				$join = new right_moojon_join($foreign_table, $local_table, $foreign_key, $local_key);
				break;
			case 'inner':
			case 'equi':
				$join = new inner_moojon_join($foreign_table, $local_table, $foreign_key, $local_key);
				break;
			case 'cross':
				$join = new cross_moojon_join($foreign_table);
				break;
		}
		return $join;
	}
	
	final static public function left($foreign_table, $local_table = null, $foreign_key = null, $local_key = null)
	{
		return self::init('left', $foreign_table, $local_table, $foreign_key, $local_key);
	}
	
	final static public function right($foreign_table, $local_table = null, $foreign_key = null, $local_key = null)
	{
		return self::init('right', $foreign_table, $local_table, $foreign_key, $local_key);
	}
	
	final static public function inner($foreign_table, $local_table = null, $foreign_key = null, $local_key = null)
	{
		return self::init('inner', $foreign_table, $local_table, $foreign_key, $local_key);
	}
	
	final static public function equi($foreign_table)
	{
		return self::inner('inner', $foreign_table);
	}
	
	final static public function cross($foreign_table)
	{
		return self::init('cross', $foreign_table);
	}
}

class joins extends moojon_joins {}
?>