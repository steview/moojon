<?php
final class second extends base_second {
	protected function add_relationships() {
		$this->has_one('first');
	}
}
?>