<?php
final class first_migration extends moojon_base_migration {
	public function up() {
		$this->create_table('users', array(
			$this->add_string('name'),
			$this->add_string('email'),
			$this->add_binary('password'),
			$this->add_date('date_column'),
			$this->add_time('time_column'),
			$this->add_float('float_column'),
			$this->add_decimal('decimal_column'),
			$this->add_timestamp('timestamp_column'),
		));
		$this->create_table('cars', array(
			$this->add_string('name'),
		));
		$this->create_table('car_users', array(
			$this->add_string('car_id'),
			$this->add_string('user_id'),
		));
		$this->create_table('posts', array(
			$this->add_string('title'),
			$this->add_text('body'),
			$this->add_boolean('publish'),
			$this->add_integer('user_id'),
		));
		$this->create_table('comments', array(
			$this->add_string('title'),
			$this->add_text('body'),
			$this->add_boolean('publish'),
			$this->add_integer('user_id'),
			$this->add_integer('post_id'),
		));
	}
	
	public function down() {
		$this->drop_table('users');
		$this->drop_table('cars');
		$this->drop_table('car_users');
		$this->drop_table('posts');
		$this->drop_table('comments');
	}
}
?>