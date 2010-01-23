<?php
final class moojon_digits_validation extends moojon_base_validation {
	
	public function valid($data) {
		return ctype_digit((string)$data['data']);
	}
}
?>