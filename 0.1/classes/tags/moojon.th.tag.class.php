<?php
final class moojon_th_tag extends moojon_base_open_tag {
	
	const NAME = 'th';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('abbr', 'align', 'axis', 'char', 'charoff', 'colspan', 'headers', 'rowspan', 'scope', 'valign', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>