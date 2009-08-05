<?php
final class moojon_exception extends Exception {
	public function __construct($message = null, $code = null, $severity = null, $file = null, $line = null) {
		if ($code == null) {
			$code = 0;
		}
		parent::__construct($message, $code);
		$this->severity = $severity;
		$this->file = $file;
		$this->line = $line;
	}
	
	public function __toString() {
		$div = new moojon_div_tag(new moojon_h1_tag($this->getMessage()), array('id' => 'exception_report'));
		$backtrace = $this->getTrace();
		$backtrace = array_reverse($backtrace);
		$ol = new moojon_ol_tag();
		foreach($backtrace as $call) {
			$string = '';
			if (array_key_exists('type', $call)) {
				switch ($call['type']) {
					case '->':
						$string .= 'method call';
						break;
					case '::':
						$string .= 'static method call';
						break;
					default:
						$string .= 'function call';
						break;
				}
			}
			$string .= self::new_line();
			$string .= 'function: '.$call['function'].self::new_line();
			if (array_key_exists('line', $call)) {
				$string .= 'line: '.$call['line'].self::new_line();
			}
			if (array_key_exists('file', $call)) {
				$string .= 'file: '.$call['file'].self::new_line();
			}
			if (array_key_exists('class', $call)) {
				$string .= 'class: '.$call['class'].self::new_line();
			}
			if (array_key_exists('object', $call)) {
				$string .= 'object:';
				$string .= self::new_line().'------------------------------------------------------------'.self::new_line();
				$string .= $call['object'];
				$string .= self::new_line().'------------------------------------------------------------'.self::new_line();
			}
			$string .= self::new_line();
			$string .= 'args:';
			$string .= self::new_line().'------------------------------------------------------------'.self::new_line();
			$string .= $call['args'];
			$string .= self::new_line().'------------------------------------------------------------'.self::new_line();
			switch (UI) {
				case 'CGI':
					$string .= '<hr />';
					break;
				case 'CLI':
					$string .= "\n++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++\n";
					break;
			}
			if (array_key_exists('file', $call) && array_key_exists('line', $call)) {
				$string = $this->get_line($call['file'], $call['line']);
			}
			$ol->add_child(new moojon_li_tag($string));
		}
		$div->add_child($ol);
		return $div->render();
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
		$return = fgets($file_handle);
		fclose($file_handle);
		return $return;
	}
}
?>