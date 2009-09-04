<?php
abstract class moojon_base_db_driver extends moojon_base {
	final private function __construct() {}
	
	final static protected  function require_prefix($subject, $prefix, $ucase = true) {
		if (!$subject) {
			return;
		}
		if (strtoupper(substr($subject, 0, strlen($prefix))) != strtoupper($prefix)) {
			$subject = $prefix.$subject;
		}
		if ($ucase) {
			$subject = strtoupper(substr($subject, 0, strlen($prefix))).substr($subject, strlen($prefix));
		}
		return $subject;
	}
}
?>