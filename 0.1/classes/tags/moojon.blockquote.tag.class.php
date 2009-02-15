<?php
final class moojon_blockquote_tag extends moojon_base_open_tag {
	
	const NAME = 'blockquote';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('cite', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>