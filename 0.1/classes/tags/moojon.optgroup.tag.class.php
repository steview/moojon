<?php
class moojon_optgroup_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'optgroup';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('label', 'disabled', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang', 'tabindex');
	}
}
?>