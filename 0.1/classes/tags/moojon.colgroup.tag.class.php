<?php
class moojon_colgroup_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'colgroup';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('align', 'char', 'charoff', 'span', 'valign', 'width', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>