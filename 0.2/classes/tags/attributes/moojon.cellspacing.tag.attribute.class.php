<?php
final class moojon_cellspacing_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'cellspacing';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>