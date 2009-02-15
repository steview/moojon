<?php
final class moojon_label_tag extends moojon_base_open_tag {
	
	const NAME = 'label';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('for', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang', 'accesskey');
	}
}
?>