<?php
final class moojon_frameset_tag extends moojon_base_open_tag {
	
	const NAME = 'frameset';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('cols', 'rows', 'id', 'class', 'title', 'style');
	}
}
?>