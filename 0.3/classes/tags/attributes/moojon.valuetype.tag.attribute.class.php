<?php
final class moojon_valuetype_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'valuetype';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>