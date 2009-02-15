<?php
final class moojon_scheme_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'scheme';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>