<?php
final class moojon_frame_tag extends moojon_base_open_tag {
	
	const NAME = 'frame';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('frameborder', 'longdesc', 'marginheight', 'marginwidth', 'name', 'noresize', 'scrolling', 'src', 'id', 'class', 'title', 'style');
	}
}
?>