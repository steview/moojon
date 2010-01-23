<?php
final class moojon_uri_validation extends moojon_base_validation {
	
	static public function valid($data) {
		return preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', (string)$data['data']);
	}
}
?>