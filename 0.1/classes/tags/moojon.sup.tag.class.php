<?php
class moojon_sup_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'sup';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>