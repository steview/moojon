<?php
final class moojon_accept_validation extends moojon_base_validation {
	
	private $exts;
	
	public function __construct($exts, $key, $message, $required = true) {
		if (!is_array($exts)) {
			$exts = explode('|', $exts);
		}
		$this->exts = $exts;
		parent::__construct($key, $message, $required);
	}
	
	public function valid($data) {
		return in_array(moojon_files::get_ext(basename($data['data'])), $this->exts);
	}
}
?>