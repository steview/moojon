<?php
final class moojon_param_tag extends moojon_base_empty_tag {
	
	const NAME = 'param';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('name', 'type', 'value', 'valuetype', 'id');
	}
}
?>