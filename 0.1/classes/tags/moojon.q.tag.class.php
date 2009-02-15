<?php
final class moojon_q_tag extends moojon_base_open_tag {
	
	const NAME = 'q';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('cite', 'datetime', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>