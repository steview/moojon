<?php
class moojon_legend_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'legend';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang', 'accesskey');
	}
}
?>