<?php
final class coaches_mailer extends moojon_base_mailer {
	static public function test($email) {
		$this->set_recipients($email);
		$this->set_from('postmaster@example.com');
		$this->set_subject('Test email subject');
		$this->set_body('Body of email');
		return $this->send();
	}
}
?>