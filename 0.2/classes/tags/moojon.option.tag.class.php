<?php
class moojon_option_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'option';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('disabled', 'label', 'selected', 'value', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>