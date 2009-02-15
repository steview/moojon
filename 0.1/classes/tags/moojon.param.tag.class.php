<?php
class moojon_param_tag extends moojon_base_empty_tag {
	
	const NODE_NAME = 'param';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('name', 'type', 'value', 'valuetype', 'id');
	}
}
?>