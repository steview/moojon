<?php
final class moojon_a_tag extends moojon_base_open_tag {
	
	const NAME = 'a';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('charset', 'coords', 'href', 'hreflang', 'name', 'rel', 'rev', 'shape', 'target', 'type', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml:lang', 'tabindex', 'accesskey');
	}
}
?>