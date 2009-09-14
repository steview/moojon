<?php
class moojon_area_tag extends moojon_base_empty_tag {
	
	const NODE_NAME = 'area';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('alt', 'coords', 'href', 'nohref', 'shape', 'target');
	}
}
?>