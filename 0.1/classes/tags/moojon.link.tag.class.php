<?php
class moojon_link_tag extends moojon_base_empty_tag {
	
	const NODE_NAME = 'link';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('charset', 'href', 'hreflang', 'media', 'rel', 'rev', 'target', 'type', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>