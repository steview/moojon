<?php
abstract class moojon_join
{
	protected $foreign_table;
	protected $foreign_key;
	protected $local_table;
	protected $local_key;
	
	final public function __construct($foreign_table, $local_table = null, $foreign_key = null, $local_key = null)
	{
		$this->foreign_table = $foreign_table;
		$this->local_table = $local_table;
		$this->foreign_key = $foreign_key;
		$this->local_key = $local_key;
	}
	
	public function render($query_builder) {}
}
?>