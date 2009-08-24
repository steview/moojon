<?php
abstract class moojon_base_validation extends moojon_base {
	
	private $message;
	private $model;
	protected $required;
	
	final public function set_model(moojon_base_model $model) {
		$this->model = $model;
	}
	
	final public function get_model() {
		return $this->model;
	}
	
	final public function set_message($message) {
		$this->message = $message;
	}
	
	final public function get_message() {
		return $this->message;
	}
	
	final public function get_required() {
		return $this->required;
	}
	
	final public function validate(moojon_base_model $model, moojon_base_column $column) {
		if ($this->required) {
			if (strlen($column->get_value())) {
				return $this->valid($model, $column);
			} else {
				return false;
			}
		} else {
			if (!strlen($column->get_value())) {
				return true;
			} else {
				return $this->valid($model, $column);
			}
		}
	}
	
	abstract public function valid(moojon_base_model $model, moojon_base_column $column);
}
?>