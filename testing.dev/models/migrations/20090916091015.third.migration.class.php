<?php
final class third_migration extends moojon_base_migration {
	public function up() {
		$this->create_table('posts', array(
			$this->add_string('title'),
			$this->add_text('body'),
		));
	}
	
	public function down() {
		$this->drop_table('posts');
	}
}
?>