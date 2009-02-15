<?php
final class moojon_col_tag extends moojon_base_empty_tag {
	
	const NAME = 'col';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('align', 'char', 'charoff', 'span', 'valign', 'width', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>