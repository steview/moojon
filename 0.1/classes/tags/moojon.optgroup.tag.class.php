<?php
final class moojon_optgroup_tag extends moojon_base_open_tag {
	
	const NAME = 'optgroup';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('label', 'disabled', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang', 'tabindex');
	}
}
?>