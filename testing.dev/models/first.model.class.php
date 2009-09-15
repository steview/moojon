<?php
final class first extends base_first {
	protected function add_relationships() {
		$this->to_string_column = 'column1';
		$this->has_many('seconds');
	}
}
?>