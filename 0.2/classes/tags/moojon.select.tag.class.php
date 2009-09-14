<?php
class moojon_select_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'select';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('disabled', 'multiple', 'name', 'size', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang', 'accesskey', 'tabindex');
	}
}
?>