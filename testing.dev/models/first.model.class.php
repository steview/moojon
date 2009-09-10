<?php
final class first extends base_first {
	protected function add_relationships() {
		$this->has_many('seconds');
	}
}
?>