<?php
class moojon_thead_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'thead';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('align', 'char', 'charoff', 'valign', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>