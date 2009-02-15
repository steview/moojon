<?php
final class moojon_link_tag extends moojon_base_empty_tag {
	
	const NAME = 'link';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('charset', 'href', 'hreflang', 'media', 'rel', 'rev', 'target', 'type', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>