<?php
final class moojon_number_validation extends moojon_base_validation {
	
	public function valid($data) {
		return is_numeric($data['data']);
	}
}
?>