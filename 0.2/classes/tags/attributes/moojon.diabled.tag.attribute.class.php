<?php
final class moojon_diabled_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'diabled';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>