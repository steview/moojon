<?php
class moojon_map_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'map';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('name', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang', 'tabindex', 'accesskey');
	}
}
?>