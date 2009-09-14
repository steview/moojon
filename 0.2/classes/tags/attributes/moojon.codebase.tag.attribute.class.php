<?php
final class moojon_codebase_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'codebase';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>