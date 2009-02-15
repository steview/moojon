<?php
final class moojon_title_tag extends moojon_base_open_tag {
	
	const NAME = 'title';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array();
	}
}
?>