<?php
final class moojon_colgroup_tag extends moojon_base_open_tag {
	
	const NAME = 'colgroup';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('align', 'char', 'charoff', 'span', 'valign', 'width', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>