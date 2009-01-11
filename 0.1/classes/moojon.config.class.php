<?php
class moojon_config {
	function __construct($config) {
		$this->update($config);
	}
	
	public function update($config = array()) {
		foreach($config as $key => $val) {
			$this->$key = $val;
		}
	}
}
?>