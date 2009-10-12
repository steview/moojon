<?php
class moojon_base_element_tag extends moojon_base_empty_tag {
	
	const NODE_NAME = 'base';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('href', 'target');
	}
}
?>