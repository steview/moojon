<?php
class moojon_br_tag extends moojon_base_empty_tag {
	
	const NODE_NAME = 'br';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('class', 'id', 'style', 'title');
	}
}
?>