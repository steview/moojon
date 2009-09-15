<?php
final class second extends base_second {
	protected function add_relationships() {
		$this->to_string_column = 'column1';
		$this->has_one('first');
	}
}
?>