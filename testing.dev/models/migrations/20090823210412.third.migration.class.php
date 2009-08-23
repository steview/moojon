<?php
final class third_migration extends moojon_base_migration {
	public function up() {
		$this->create_table('table3', array($this->add_string('column1')));
	}
	
	public function down() {
		$this->remove_table('table3');
	}
}
?>