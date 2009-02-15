<?php
class moojon_frameset_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'frameset';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('cols', 'rows', 'id', 'class', 'title', 'style');
	}
}
?>