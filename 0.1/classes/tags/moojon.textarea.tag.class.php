<?php
class moojon_textarea_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'textarea';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('cols', 'rows', 'diabled', 'name', 'readonly', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang', 'tabindex', 'accesskey');
	}
}
?>