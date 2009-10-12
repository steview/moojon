<?php
final class moojon_classid_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'classid';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>