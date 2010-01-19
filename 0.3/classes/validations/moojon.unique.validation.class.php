<?php
final class moojon_unique_validation extends moojon_base_validation {
	
	public function get_data_keys() {
		return array('data', 'data_set');
	}
	
	public function valid($data) {
		return (!in_array($data['data'], $data['data_set']));
	}
}
?>