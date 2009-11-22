<?php
final class salter extends moojon_base {
	const SALT_LENGTH = 32;
	
	static public function generate_salt($length = null) {
		$length = ($length <= self::SALT_LENGTH) ? $length : self::SALT_LENGTH;
		return substr(md5(uniqid(rand(), true)), 0, $length);
	}
	
	static public function hash($subject, $salt = null) {
		$salt = ($salt) ? substr($salt, 0, SALT_LENGTH) : self::generate_salt();
		return $salt.sha1($salt.$subject);
	}
}
?>