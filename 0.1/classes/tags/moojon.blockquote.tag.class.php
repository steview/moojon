<?php
class moojon_blockquote_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'blockquote';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('cite', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>