<?php
final class moojon_span_tag extends moojon_base_open_tag {
	
	const NAME = 'span';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>