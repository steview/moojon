<?php
final class moojon_meta_tag extends moojon_base_empty_tag {
	
	const NAME = 'meta';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('content', 'http_equiv', 'name', 'scheme', 'dir', 'lang', 'xml_lang');
	}
}
?>