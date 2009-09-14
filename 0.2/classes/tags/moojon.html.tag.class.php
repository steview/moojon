<?php
class moojon_html_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'html';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('xmlns');
	}
}
?>