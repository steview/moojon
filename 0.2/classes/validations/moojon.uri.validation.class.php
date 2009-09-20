<?php
final class moojon_uri_validation extends moojon_base_validation {
	
	public function __construct($message, $required = true) {
		$this->set_message($message);
		$this->required = $required;
	}
	
	public function valid(moojon_base_model $model, moojon_base_column $column) {
		return preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $column->get_value());
	}
}
?>