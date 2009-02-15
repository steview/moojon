<?php
final class moojon_standby_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'standby';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>