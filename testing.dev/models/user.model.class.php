<?php
final class user extends base_user {
	protected function add_relationships() {
		$this->has_many('posts');
	}
	
	protected function add_validations() {
		//$this->validate_accept('name', 'The message', array('png'));
	}
	
	public function set_password($value) {
		if (!$this->salt) {
			$this->salt = salter::generate_salt();
		}
		return salter::hash($value, $this->salt);
	}
}
?>