<?php
class moojon_object_tag extends moojon_base_open_tag {
	
	const NODE_NAME = 'object';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('align', 'archive', 'border', 'classid', 'codebase', 'codetype', 'data', 'declare', 'height', 'hspace', 'name', 'standby', 'type', 'usemap', 'vspace', 'widthclass', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang', 'accesskey', 'tabindex');
	}
}
?>