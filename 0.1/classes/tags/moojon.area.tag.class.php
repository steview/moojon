<?php
final class moojon_area_tag extends moojon_base_empty_tag {
	
	const NAME = 'area';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('alt', 'coords', 'href', 'nohref', 'shape', 'target');
	}
}
?>