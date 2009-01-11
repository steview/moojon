<?php
abstract class moojon_base_relationship extends moojon_base
{
	protected $foreign_obj;
	protected $foreign_key;
	protected $key;
	
	final public function __construct($foreign_obj, $foreign_key, $key) {
		if (substr($foreign_obj, 0, 5) == 'base_') {
			$foreign_obj = substr($foreign_obj, 5);
		}
		$this->foreign_obj = $foreign_obj;
		$this->foreign_key = $foreign_key;
		$this->key = $key;		
	}
	
	final public function foreign_obj() {
		return $this->foreign_obj;
	}
	
	final public function foreign_key() {
		return $this->foreign_key;
	}
	
	final public function get_key() {
		return $this->key;
	}
	
	abstract function get_where(base_moojon_model $accessor);
}
?>