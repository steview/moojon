<?php
class moojon_script_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'script';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('type', 'charset', 'defer', 'src', 'xml_space');
	}
}
?>