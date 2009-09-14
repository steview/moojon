<?php
final class moojon_size_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'size';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>