<?php
final class moojon_console_cli extends moojon_base_cli {
	public function run($arguments) {
		while (1) {
			$prompt = self::prompt('console');
			if (!eval($prompt)) {
				$prompt = "return $prompt";
			}
			echo "\n($prompt)\n";
			var_dump(eval($prompt));
		}
	}
}
?>