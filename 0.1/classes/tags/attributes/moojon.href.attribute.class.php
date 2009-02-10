<?php
final class moojon_href_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'href';
	
	final static public function initr($value = null, $legal_values = null) {
		return self::base_initr(self::NAME, $value, $legal_values);
	}
	
	final static public function init($value = null) {
		$legal_values = array();
		return self::base_init(self::NAME, $value, $legal_values);
	}
}
?>