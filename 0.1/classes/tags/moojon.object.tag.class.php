<?php
final class moojon_object_tag extends moojon_base_open_tag {
	
	const NAME = 'object';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('align', 'archive', 'border', 'classid', 'codebase', 'codetype', 'data', 'declare', 'height', 'hspace', 'name', 'standby', 'type', 'usemap', 'vspace', 'widthclass', 'id', 'style', 'title', 'dir', 'lang', 'xml_lang', 'accesskey', 'tabindex');
	}
}
?>