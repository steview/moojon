<?php
final class moojon_profile_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'profile';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>