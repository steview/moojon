<?php
final class moojon_script_tag extends moojon_base_open_tag {
	
	const NAME = 'script';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('type', 'charset', 'defer', 'src', 'xml_space');
	}
}
?>