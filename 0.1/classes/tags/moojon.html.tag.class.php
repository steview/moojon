<?php
final class moojon_html_tag extends moojon_base_open_tag {
	
	const NAME = 'html';
	
	protected function init() {
		$this->name = self::NAME;
		$this->legal_attributes = array('xmlns');
	}
}
?>