<?php
final class moojon_xml_space_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'xml_space';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>