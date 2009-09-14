<?php
final class moojon_data_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'data';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>