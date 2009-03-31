<?php
final class moojon_accept_validation extends moojon_base_validation {
	
	private $exts;
	
	public function __construct($message, $exts, $required = true) {
		$this->set_message($message);
		$this->exts = $exts;
		$this->required = $required;
	}
	
	public function get_exts() {
		return $this->exts;
	}
	
	public function valid(moojon_base_model $model, moojon_base_column $column) {
		return in_array(moojon_files::get_ext(basename($column->get_value())), $this->exts);
	}
}
?>