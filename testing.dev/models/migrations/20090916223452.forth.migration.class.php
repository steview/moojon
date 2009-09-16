<?php
final class forth_migration extends moojon_base_migration {
	public function up() {
		$this->create_table('thirds', array(
			$this->add_date('date_column'),
			$this->add_time('time_column'),
		));
	}
	
	public function down() {
		$this->drop_table('thirds');
	}
}
?>