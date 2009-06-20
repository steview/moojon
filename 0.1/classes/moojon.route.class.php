<?php
final class moojon_route extends moojon_base_route {
	public function map_uri($uri) {
		if ($this->match_count($uri)) {
			$patterns = explode('/', $this->pattern);
			$symbols = array();
			$return = array();
			for ($i = 0; $i < count($patterns); $i ++) {
				if ($this->is_symbol($patterns[$i])) {
					$pattern_name = strtolower($this->get_symbol_name($patterns[$i]));
					if (!in_array($pattern_name, $symbols)) {
						$symbols[] = $pattern_name;
					} else {
						die('Duplicate symbol key in route ('.$patterns[$i].') '.$this->pattern);
					}
					$return[$pattern_name] = $patterns[$i];
				}
			}
			$uris = explode('/', $uri);
			foreach ($this->params as $key => $value) {
				$return[$key] = $value;
			}
			for ($i = 0; $i < count($patterns); $i ++) {
				if ($this->is_symbol($patterns[$i])) {
					switch ($this->get_symbol_name($patterns[$i])) {
						case 'app':
							if (moojon_paths::get_app_path($uris[$i])) {
								$return['app'] = $uris[$i];
							} else {
								return false;
							}
							break;
						case 'controller':
							if (moojon_paths::get_controller_path($return['app'], $uris[$i])) {
								$return['controller'] = $uris[$i];
							} else {
								return false;
							}
							break;
						default:
							$return[$this->get_symbol_name($patterns[$i])] = $uris[$i];
							break;
					}
				} else {
					if ($patterns[$i] != $uris[$i]) {
						return false;
					}
				}
			}
			if (array_key_exists('app', $return) == false || array_key_exists('controller', $return) == false || array_key_exists('action', $return) == false) {
				return false;
			}
			require_once(moojon_paths::get_controller_path($return['app'], $return['controller']));
			if (!method_exists(self::get_controller_class($return['controller']), $return['action']) && !moojon_paths::get_view_path($return['app'], $return['controller'], $return['action'])) {
				return false;
			}
			return $return;
		} else {
			return false;
		}
	}
}
?>