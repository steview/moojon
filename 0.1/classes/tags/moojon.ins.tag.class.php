<?php
class moojon_ins_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'ins';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('cite', 'datetime', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>