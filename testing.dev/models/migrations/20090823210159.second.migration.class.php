<?php
final class second_migration extends moojon_base_migration {
	public function up() {
		$this->create_table('table2', array($this->add_string('column1')));
	}
	
	public function down() {
		$this->remove_table('table2');
	}
}
?>