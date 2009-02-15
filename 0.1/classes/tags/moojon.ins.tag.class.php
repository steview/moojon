<?php
final class moojon_ins_tag extends moojon_base_open_tag {
	
	const NAME = 'ins';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('cite', 'datetime', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>