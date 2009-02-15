<?php
final class moojon_img_tag extends moojon_base_empty_tag {
	
	const NAME = 'img';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('alt', 'src', 'height', 'ismap', 'logdesc', 'usemap', 'width');
	}
}
?>