<?php
final class moojon_accept_validation extends moojon_base_validation {
	
	private $exts;
	
	public function __construct($message, $exts) {
		$this->set_message($message);
		$this->exts = $exts;
	}
	
	public function validate(moojon_base_column $column) {
		return in_array(moojon_files::get_ext(basename($column->get_value())), $this->exts);
	}
}
?>