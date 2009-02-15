<?php
final class moojon_br_tag extends moojon_base_empty_tag {
	
	const NAME = 'br';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('class', 'id', 'style', 'title');
	}
}
?>