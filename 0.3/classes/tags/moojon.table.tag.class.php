<?php
class moojon_table_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'table';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('border', 'cellpadding', 'cellspacing', 'frame', 'rules', 'summary', 'width', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>