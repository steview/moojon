<?php
final class moojon_small_tag extends moojon_base_open_tag {
	
	const NAME = 'small';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('class', 'id', 'style', 'title', 'dir', 'lang');
	}
}
?>