<?php
final class post extends base_post {
	protected function add_relationships() {
		$this->has_one('user');
	}
}
?>