<?php
final class moojon_select_tag extends moojon_base_open_tag {
	
	const NAME = 'select';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('disabled', 'multiple', 'name', 'size', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang', 'accesskey', 'tabindex');
	}
}
?>