<?php
final class moojon_past_validation extends moojon_base_validation {
	
	public function valid($data) {
		return (time() > strtotime($data['data']));
	}
}
?>