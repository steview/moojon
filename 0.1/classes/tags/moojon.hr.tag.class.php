<?php
final class moojon_hr_tag extends moojon_base_empty_tag {
	
	const NAME = 'hr';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>