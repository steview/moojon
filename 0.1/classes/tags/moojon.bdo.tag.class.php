<?php
class moojon_bdo_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'bdo';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('dir', 'class', 'id', 'style', 'title', 'lang', 'xml_lang');
	}
}
?>