<?php
final class moojon_form_tag extends moojon_base_open_tag {
	
	const NAME = 'form';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('action', 'accept', 'accept_charset', 'enctype', 'method', 'name', 'target', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>