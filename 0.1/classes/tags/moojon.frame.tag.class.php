<?php
class moojon_frame_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'frame';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('frameborder', 'longdesc', 'marginheight', 'marginwidth', 'name', 'noresize', 'scrolling', 'src', 'id', 'class', 'title', 'style');
	}
}
?>