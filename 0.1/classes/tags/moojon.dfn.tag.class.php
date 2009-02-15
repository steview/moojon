<?php
final class moojon_dfn_tag extends moojon_base_open_tag {
	
	const NAME = 'dfn';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>