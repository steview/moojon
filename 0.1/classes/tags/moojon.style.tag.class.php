<?php
final class moojon_style_tag extends moojon_base_open_tag {
	
	const NAME = 'style';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('type', 'media', 'title', 'dir', 'lang', 'xml_space');
	}
}
?>