<?php
final class moojon_bdo_tag extends moojon_base_open_tag {
	
	const NAME = 'bdo';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('dir', 'class', 'id', 'style', 'title', 'lang', 'xml_lang');
	}
}
?>