<?php
final class post extends base_post {
	protected function add_relationships() {
		$this->to_string_column = 'title';
	}
	
	protected function add_validations() {
		//$this->validate_required('title', 'Please supply a title for this post');
	}
}
?>