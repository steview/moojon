<?php
final class moojon_route extends moojon_base_route {
	protected function init() {}
	
	public function map($uri, $validate = true) {
		$uris = explode('/', $uri);
		if (count($uris) == count($this->patterns)) {
			$test = true;
			$data = $this->params;
			for ($i = 0; $i < count($this->patterns); $i ++) {
				if (self::is_symbol($this->patterns[$i])) {
					$data[self::get_symbol_name($this->patterns[$i])] = $uris[$i];
				} else {
					if ($this->patterns[$i] != $uris[$i]) {
						$test = false;
						break;
					}
				}
			}
			if ($test && $this->validate_matches($data, $validate)) {
				return new moojon_route_match($this->pattern, $data);
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function map_uri($uri, $validate = true) {
		return $this->pattern;
	}
}
?>