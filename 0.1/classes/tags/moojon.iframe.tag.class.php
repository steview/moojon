<?php
final class moojon_iframe_tag extends moojon_base_open_tag {
	
	const NAME = 'iframe';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('frameborder', 'height', 'longdesc', 'marginheight', 'marginwidth', 'name', 'scrolling', 'src', 'width');
	}
}
?>