<?php
class moojon_title_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'title';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array();
	}
}
?>