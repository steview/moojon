<?php
final class moojon_url_validation extends moojon_base_validation {
	
	public function __construct($message) {
		$this->set_message($message);
	}
	
	public function validate(moojon_base_model $model, moojon_base_column $column) {
		return preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $column->get_name());
	}
}
?>