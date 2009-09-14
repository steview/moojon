<?php
class moojon_col_tag extends moojon_base_empty_tag {
	
	const NODE_NAME = 'col';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('align', 'char', 'charoff', 'span', 'valign', 'width', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>