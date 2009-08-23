<?php
final class first_migration extends moojon_base_migration {
	public function up() {
		$this->create_table('table1', array($this->add_string('column1')));
	}
	
	public function down() {
		$this->remove_table('table1');
	}
}
?>