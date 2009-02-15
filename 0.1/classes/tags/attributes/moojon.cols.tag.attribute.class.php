<?php
final class moojon_cols_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'cols';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>