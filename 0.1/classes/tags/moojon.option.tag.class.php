<?php
final class moojon_option_tag extends moojon_base_open_tag {
	
	const NAME = 'option';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('disabled', 'label', 'selected', 'value', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>