<?php
abstract class moojon_base_mailer extends moojon_base {
	final public function __construct() {}
	
	final protected function set_recipients($recipients) {}
	
	final protected function set_from($from) {}
	
	final protected function set_subject($subject) {}
	
	final protected function set_body($body) {}
	
	final potected function send() {
		return true;
	}
}
?>