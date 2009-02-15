<?php
class moojon_iframe_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'iframe';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('frameborder', 'height', 'longdesc', 'marginheight', 'marginwidth', 'name', 'scrolling', 'src', 'width');
	}
}
?>