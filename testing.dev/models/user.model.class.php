<?php
final class user extends base_user {
	protected function add_relationships() {
		$this->has_many('posts');
	}
}
?>