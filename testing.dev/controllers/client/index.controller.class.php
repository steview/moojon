<?php
final class index_controller extends moojon_base_controller {
	public function index() {
		$this->forward('client/index/test');
	}
	
	public function test() {
		echo "<h1>test() ".$this->get_view()."</h1>";
	}
}
?>