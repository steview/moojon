<?php
final class moojon_textarea_tag extends moojon_base_open_tag {
	
	const NAME = 'textarea';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('cols', 'rows', 'diabled', 'name', 'readonly', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang', 'tabindex', 'accesskey');
	}
}
?>