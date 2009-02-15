<?php
class moojon_meta_tag extends moojon_base_empty_tag {
	
	const NODE_NAME = 'meta';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('content', 'http_equiv', 'name', 'scheme', 'dir', 'lang', 'xml_lang');
	}
}
?>