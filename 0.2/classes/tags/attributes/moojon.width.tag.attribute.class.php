<?php
final class moojon_width_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'width';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>