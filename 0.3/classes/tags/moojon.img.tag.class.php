<?php
class moojon_img_tag extends moojon_base_empty_tag {
	
	const NODE_NAME = 'img';
	
	protected function init() {
		$this->node_name = self::NODE_NAME;
		$this->legal_attributes = array('alt', 'src', 'height', 'ismap', 'logdesc', 'usemap', 'width', 'id');
	}
}
?>