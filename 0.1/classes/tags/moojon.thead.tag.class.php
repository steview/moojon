<?php
final class moojon_thead_tag extends moojon_base_open_tag {
	
	const NAME = 'thead';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('align', 'char', 'charoff', 'valign', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>