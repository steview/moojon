<?php
final class user extends base_user {
	protected function add_validations() {
		$this->validate_digits('name', 'Name must be all digits');
	}
}
?>