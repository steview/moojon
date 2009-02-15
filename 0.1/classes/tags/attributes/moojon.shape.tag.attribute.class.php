<?php
final class moojon_shape_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'shape';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>