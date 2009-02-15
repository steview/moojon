<?php
final class moojon_head_tag extends moojon_base_open_tag {
	
	const NAME = 'head';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('profile', 'dir', 'lang', 'xml_lang');
	}
}
?>