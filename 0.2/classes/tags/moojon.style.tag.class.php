<?php
class moojon_style_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'style';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('type', 'media', 'title', 'dir', 'lang', 'xml_space');
	}
}
?>