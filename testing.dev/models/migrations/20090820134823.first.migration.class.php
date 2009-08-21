<?php
final class first_migration extends moojon_base_migration {
	public function up() {
		echo "up1\n";
		$this->create_table('table1', array($this->add_string('column1')));
		echo "up2\n";
	}
	
	public function down() {
		$this->drop_table('table1');
	}
}
?>