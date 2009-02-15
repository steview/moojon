<?php
class moojon_label_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'label';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('for', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang', 'accesskey');
	}
}
?>