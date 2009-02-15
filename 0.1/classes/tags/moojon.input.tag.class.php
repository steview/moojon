<?php
final class moojon_input_tag extends moojon_base_empty_tag {
	
	const NAME = 'input';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('accept', 'align', 'alt', 'checked', 'disabled', 'maxlength', 'name', 'readonly', 'size', 'src', 'type', 'value', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang', 'tabindex', 'accesskey');
	}
}
?>