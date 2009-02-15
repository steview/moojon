<?php
final class moojon_value_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'value';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>