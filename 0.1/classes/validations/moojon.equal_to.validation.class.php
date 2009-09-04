<?php
final class moojon_equal_to_validation extends moojon_base_validation {
	
	private $name;
	
	public function __construct($message, $name, $required = true) {
		$this->set_message($message);
		$this->name = $name;
		$this->required = $required;
	}
	
	public function get_name() {
		return $this->name;
	}
	
	public function valid(moojon_base_model $model, moojon_base_column $column) {
		$columns = moojon_uri::get(get_class($model));
		return ($columns[$this->name] == $column->get_value());
	}
}
?>