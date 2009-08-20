<?php
final class moojon_exception extends Exception {
	static private $instance;
	
	public function __construct($message = null, $code = null, $severity = null, $file = null, $line = null) {
		if ($code == null) {
			$code = 0;
		}
		parent::__construct($message, $code);
		$this->severity = $severity;
		$this->file = $file;
		$this->line = $line;
		self::$instance = $this;
	}
	
	static private function get() {
		if (!self::$instance) {
			self::$instance = new moojon_excption();
		}
		return self::$instance;
	}
	
	static public function find() {
		return self::$instance;
	}
	
	public function __toString() {
		$div = new moojon_div_tag(new moojon_h1_tag($this->getMessage()), array('id' => 'exception_report'));
		$report = "\n".$this->getMessage()."\n\n";
		$ol = new moojon_ol_tag();
		$counter = 0;
		foreach($this->getTrace() as $call) {
			$string = '';
			if (array_key_exists('file', $call) && array_key_exists('line', $call)) {
				$counter ++;
				$string = $call['file'].' on line '.$call['line'].' ('.$this->get_line($call['file'], $call['line']).')';
				$report .= "\033[0m $counter. ".$this->get_line($call['file'], $call['line'])." \033[31m".$call['file'].' on line '.$call['line']."\n";
			}
			$ol->add_child(new moojon_li_tag($string));
		}
		$div->add_child($ol);
		switch (UI) {
			case 'CGI':
				return $div->render();
				break;
			case 'CLI':
				return "$report\033[0m";
				break;
		}
	}
	
	static public function new_line() {
		switch (UI) {
			case 'CGI':
				return '<br />';
				break;
			case 'CLI':
				return "\n";
				break;
		}
	}
	
	static private function get_line($path, $line) {
		$file_handle = fopen($path, 'r');
		for ($i = 1; $i < $line; $i ++) {
			fgets($file_handle);
		}
		$return = trim(fgets($file_handle));
		fclose($file_handle);
		return $return;
	}
}
?>