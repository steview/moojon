<?php
final class moojon_border_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'border';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>