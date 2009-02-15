<?php
final class moojon_accept_charset_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'accept_charset';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>