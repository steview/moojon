<?php
final class moojon_frame_tag_attribute extends moojon_base_tag_attribute {
	
	const NAME = 'frame';
	
	final public function init($value = null) {
		$legal_values = array();
		$this->name = self::NAME;
	}
}
?>