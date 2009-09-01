<?php
final class first_migration extends moojon_base_migration {
	public function up() {
		$this->create_table('first', array($this->add_string('column1')));
	}
	
	public function down() {
		$this->drop_table('first');
	}
}
?>