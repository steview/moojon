<?php
final class moojon_equal_to_validation extends moojon_base_validation {
	
	public function get_data_keys() {
		return array('data', 'equal_to');
	}
	
	static public function valid($data) {
		return ($data['data'] == $data['equal_to']);
	}
}
?>