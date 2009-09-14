<?php
final class moojon_for_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'for';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>