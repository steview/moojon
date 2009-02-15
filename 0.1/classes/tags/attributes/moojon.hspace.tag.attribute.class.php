<?php
final class moojon_hspace_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'hspace';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>