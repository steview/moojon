<?php
final class moojon_route extends moojon_base_route {
	public function map_uri($uri) {
		if ($this->match_count($uri)) {
			echo $uri.' '.count(explode('/', $uri)).' '.$this->pattern.' '.count(explode('/', $this->pattern)).' map_uri<br />';
			$pattern = explode('/', $this->pattern);
			$symbols = array();
			$return = array();
			for ($i = 0; $i < count($pattern); $i ++) {
				if ($this->is_symbol($pattern[$i])) {
					$pattern_name = strtolower($this->get_symbol_name($pattern[$i]));
					if (!in_array($pattern_name, $symbols)) {
						$symbols[] = $pattern_name;
					} else {
						die('Duplicate symbol key in route ('.$pattern[$i].') '.$this->pattern);
					}
					$return[$pattern_name] = $pattern[$i];
				}
			}
			foreach ($this->params as $key => $value) {
				$return[$key] = $value;
			}
			if (array_key_exists('app', $return) == false || array_key_exists('controller', $return) == false || array_key_exists('action', $return) == false) {
				return false;
			}
			if (!in_array($return['app'], moojon_uri::get_apps())) {
				return false;
			}
			/*if (!in_array($return['controller'], moojon_uri::get_controllers($return['app']))) {
				return false;
			}*/
			if (!in_array($return['action'], moojon_uri::get_actions($return['controller']))) {
				return false;
			}
			return $return;
		} else {
			return false;
		}
	}
}
?>