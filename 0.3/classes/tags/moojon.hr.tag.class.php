<?php
class moojon_hr_tag extends moojon_base_empty_tag {
	
	const NODE_NAME = 'hr';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>