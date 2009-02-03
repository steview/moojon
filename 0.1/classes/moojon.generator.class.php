<?php
final class moojon_generator extends moojon_base {	
	private function __construct() {}
	
	static public function run($template, $destination, $swaps) {
		if (!$handle = fopen($template, 'r')) {
			fclose($handle);
			self::handle_error("Unable to open template file for reading ($template)");
		}
		$template = fread($handle, filesize($template));
		fclose($handle);
		if (!$handle = fopen($destination, 'w')) {
			fclose($handle);
			self::handle_error("Unable to open destination file for writing ($destination)");
		}
		if (fwrite($handle, self::swap_out($template, $swaps, '<[', ']>')) === false) {
			fclose($handle);
			self::handle_error("Unable to write destination file ($destination)");
		}
		fclose($handle);
	}
	
	static public function swap_out($text, $swaps, $begin = null, $end = null) {
		if (!is_array($swaps)) {
			$swaps = array($swaps);
		}
		foreach ($swaps as $key => $value) {
			$text = str_replace("$begin$key$end", $value, $text);
		}
		return $text;
	}
}
?>