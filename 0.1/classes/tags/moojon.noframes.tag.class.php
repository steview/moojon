<?php
final class moojon_noframes_tag extends moojon_base_open_tag {
	
	const NAME = 'noframes';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>