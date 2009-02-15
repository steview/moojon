<?php
final class moojon_table_tag extends moojon_base_open_tag {
	
	const NAME = 'table';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('border', 'cellpadding', 'cellspacing', 'frame', 'rules', 'summary', 'width', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>