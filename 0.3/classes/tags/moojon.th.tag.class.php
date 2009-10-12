<?php
class moojon_th_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'th';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('abbr', 'align', 'axis', 'char', 'charoff', 'colspan', 'headers', 'rowspan', 'scope', 'valign', 'class', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang');
	}
}
?>