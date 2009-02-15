<?php
final class moojon_tr_tag extends moojon_base_open_tag {
	
	const NAME = 'tr';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('align', 'char', 'charoff', 'valign', 'align', 'char', 'charoff', 'valign', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>