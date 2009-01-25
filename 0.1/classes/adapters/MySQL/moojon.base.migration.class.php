<?php
abstract class moojon_base_migration extends moojon_base {
	final public function __construct() {}
		
	abstract public function up();
	
	abstract public function down();
}
?>