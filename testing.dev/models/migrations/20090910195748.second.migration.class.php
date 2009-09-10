<?php
final class second_migration extends moojon_base_migration {
	public function up() {
		$this->create_table('seconds', array(
			$this->add_string('column1'),
			$this->add_integer('first_id'),
		));
	}
	
	public function down() {
		$this->drop_table('seconds');
	}
}
?>