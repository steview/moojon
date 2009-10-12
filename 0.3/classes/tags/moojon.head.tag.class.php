<?php
class moojon_head_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'head';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('profile', 'dir', 'lang', 'xml_lang');
	}
}
?>