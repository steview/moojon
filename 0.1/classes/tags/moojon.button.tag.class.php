<?php
final class moojon_button_tag extends moojon_base_open_tag {
	
	const NAME = 'button';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('disabled', 'name', 'type', 'value', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang', 'tabindex', 'accesskey');
	}
}
?>