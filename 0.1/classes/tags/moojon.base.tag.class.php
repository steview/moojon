<?php
final class moojon_base_element_tag extends moojon_base_empty_tag {
	
	const NAME = 'base';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('href', 'target');
	}
}
?>