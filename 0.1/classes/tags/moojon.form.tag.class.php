<?php
class moojon_form_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'form';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('action', 'accept', 'accept_charset', 'enctype', 'method', 'name', 'target', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>