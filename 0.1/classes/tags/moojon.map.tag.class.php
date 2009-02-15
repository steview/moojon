<?php
final class moojon_map_tag extends moojon_base_open_tag {
	
	const NAME = 'map';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('name', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang', 'tabindex', 'accesskey');
	}
}
?>