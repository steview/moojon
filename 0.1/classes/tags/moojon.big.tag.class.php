<?php
final class moojon_big_tag extends moojon_base_open_tag {
	
	const NAME = 'big';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('class', 'id', 'style', 'title', 'dir', 'lang');
	}
}
?>