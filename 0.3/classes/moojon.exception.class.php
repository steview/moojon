<?php
final class moojon_exception extends Exception {
	static private $instance;
	
	public function __construct($message = null, $code = null, $severity = null, $file = null, $line = null) {
		if (!$code) {
			$code = 0;
		}
		$code = ($code) ? $code : 0;
		$severity = ($severity) ? $severity : 0;
		$backtrace = debug_backtrace();
		die("<h1>$message: $file ($line)</h1>\n".print_r($backtrace, true));
		$file = ($file) ? $file : $backtrace[0]['file'];
		$line = ($line) ? $line : $backtrace[0]['line'];
		parent::__construct($message, $code);
		$this->severity = $severity;
		$this->file = $file;
		$this->line = $line;
		//$mailer = moojon_mailer::from_html("<h1>$message: $file ($line)</h1>".$this->get_trace_ol($backtrace));
		die("<h1>$message: $file ($line)</h1>".(string)$this);
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
		$trace = $this->getTrace();
		$exception = $trace[0]['args'][0];
		$div = new moojon_div_tag(
			new moojon_h1_tag(
				$this->getMessage(), 
				array(
					'title' => $this->get_line($exception->getFile(), $exception->getLine())
				)
			), 
			array('id' => 'exception_report')
		);
		$report = "\n".$this->getMessage()."\n\n".$this->get_trace_report($this->getTrace());
		//$div->add_child($this->get_trace_ol());
		switch (UI) {
			case 'CGI':
				return $div->render();
				break;
			case 'CLI':
				return "$report\033[0m";
				break;
		}
		return $div;
	}
	
	private function get_trace_ol($trace = array()) {
		$ol = new moojon_ol_tag();
		$counter = 0;
		foreach ($trace[0]['args'][0]->getTrace() as $call) {
			if (array_key_exists('file', $call) && array_key_exists('file', $call)) {
				$counter ++;
				$ol->add_child(new moojon_li_tag(array(new moojon_h2_tag($call['file'].' line: '.$call['line'], array('class' => 'line', 'id' => "call$counter", 'title' => $this->get_line($call['file'], $call['line']))), new moojon_div_tag($this->return_source($call['file'], $call['line']), array('class' => 'call', 'id' => "call$counter".'source')))));
			}
		}
		return $ol;
	}
	
	private function get_trace_report($trace = array()) {
		$report = '';
		$trace = $this->getTrace();
		$counter = 0;
		foreach ($trace[0]['args'][0]->getTrace() as $call) {
			if (array_key_exists('file', $call) && array_key_exists('file', $call)) {
				$counter ++;
				$report .= "\033[0m $counter. ".$this->get_line($call['file'], $call['line'])." \033[31m".$call['file'].' on line '.$call['line']."\n";
			}
		}
		return $report;
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
	
	static private function return_source($path, $line_number) {
		$file_handle = fopen($path, 'r');
		$ol = new moojon_ol_tag();
		$counter = 0;
		while ($line = fgets($file_handle)) {
			$counter ++;
			$start = false;
			$end = false;
			if (substr($line, 0, 2) != '<?' && substr($line, 0, 5) != '<?php') {
				$line = "<?php$line";
				$start = true;
			}
			if (substr($line, -2) != '?>') {
				$line = "$line?>";
				$end = true;
			}
			$line = highlight_string($line, true);
			if ($end) {
				$line = substr($line, 0, strrpos($line, '?&gt;')).substr($line, (strrpos($line, '?&gt;') + 5));
			}
			if ($start) {
				$line = substr($line, 0, strpos($line, '&lt;?php')).substr($line, (strpos($line, '&lt;?php') + 8));
			}
			$attributes = ($counter == $line_number) ? array('style' => 'background-color:#FFC;') : array();
			$ol->add_child(new moojon_li_tag($line, $attributes));
		}
		fclose($file_handle);
		return $ol->render();
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