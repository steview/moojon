<?php
final class moojon_future_validation extends moojon_base_validation {
	
	public function valid($data) {
		return (time() < strtotime($data['data']));
	}
}
?>