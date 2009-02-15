<?php
final class moojon_rel_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'rel';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>