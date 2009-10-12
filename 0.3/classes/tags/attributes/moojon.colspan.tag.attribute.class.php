<?php
final class moojon_colspan_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'colspan';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>