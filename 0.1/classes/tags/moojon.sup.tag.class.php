<?php
final class moojon_sup_tag extends moojon_base_open_tag {
	
	const NAME = 'sup';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>