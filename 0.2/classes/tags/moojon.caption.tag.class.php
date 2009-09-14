<?php
class moojon_caption_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'caption';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>