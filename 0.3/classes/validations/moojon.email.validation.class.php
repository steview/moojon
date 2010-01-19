<?php
final class moojon_email_validation extends moojon_base_validation {
	
	public function valid($data) {
		return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $data['data']);
	}
}
?>