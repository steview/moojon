<?php
final class moojon_cite_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'cite';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>