 <?php
final class moojon_quick_tags extends moojon_base {
	private function __construct() {}
	
	static public function link_to($text, $action, $controller = null, $app = null, $attributes = array()) {
		if (array_key_exists('href', $attributes) == false) {
			if ($controller == null) {
				$controller = moojon_uri::get_controller();
			}
			if ($app == null) {
				$app = moojon_uri::get_app();
			}
			$attributes['href'] = moojon_config::get('index_file')."$app/$controller/$action";
		}
		return new moojon_a_tag($text, $attributes);
	}
	
	static public function year_select_options($start = null, $end = null, $attributes = null, $selected = null, $format = null) {
		if ($selected == null) {
			$selected = date('Y');
		}
		return self::select_options(self::year_options($start, $end, $format), $selected, $attributes);
	}
	
	static public function year_options($start = null, $end = null, $format = null) {
		if ($format != 'Y' && $format != 'y') {
			$format = 'Y';
		}
		$years = array();
		if ($start == null) {
			$start = abs(date('Y') - 50);
		}
		if ($end == null) {
			$end = abs(date('Y') + 50);
		}
		for($i = min($start, $end); $i < (max($start, $end) + 1); $i ++) {
			$value = $i;
			if ($format == 'y') {
				$years[substr($value, -2)] = $value;
			} else {
				$years[$value] = $value;
			}
		}
		return $years;
	}
	
	static public function month_select_options($attributes = null, $selected = null, $format = null) {
		if ($selected == null) {
			$selected = date('n');
		}
		return self::select_options(self::month_options($format), $selected, $attributes);
	}
	
	static public function month_options($format = null) {
		if ($format != 'm' && $format != 'n') {
			$format = 'm';
		}
		$months = array();
		for($i = 1; $i < 13; $i ++) {
			if ($i < 10 && $format == 'm') {
				$key = "0$i";
			} else {
				$key = $i;
			}
			$months[$key] = $i;
		}
		return $months;
	}
	
	static public function day_select_options($attributes = null, $selected = null, $format = null) {
		if ($selected == null) {
			$selected = date('j');
		}
		return self::select_options(self::day_options($format), $selected, $attributes);
	}
	
	static public function day_options($format = null) {
		if ($format != 'd' && $format != 'j') {
			$format = 'd';
		}
		$days = array();
		for($i = 1; $i < 31; $i ++) {
			if ($i < 10 && $format == 'd') {
				$key = "0$i";
			} else {
				$key = $i;
			}
			$days[$key] = $i;
		}
		return $days;
	}
	
	static public function hour_select_options($attributes = null, $selected = null, $format = null) {
		if ($selected == null) {
			$selected = date('G');
		}
		return self::select_options(self::hour_options($format), $selected, $attributes);
	}
	
	static public function hour_options($format = null) {
		if ($format != 'G' && $format != 'H') {
			$format = 'G';
		}
		$hours = array();
		for($i = 0; $i < 24; $i ++) {
			if ($i < 10 && $format == 'G') {
				$key = "0$i";
			} else {
				$key = $i;
			}
			$hours[$key] = $i;
		}
		return $hours;
	}
	
	static public function minute_select_options($attributes = null, $selected = null) {
		if ($selected == null) {
			$selected = date('i');
		}
		return self::select_options(self::minute_options(), $selected, $attributes);
	}
	
	static public function minute_options() {
		$minutes = array();
		for($i = 0; $i < 60; $i ++) {
			if ($i < 10) {
				$key = "0$i";
			} else {
				$key = $i;
			}
			$minutes[$key] = $i;
		}
		return $minutes;
	}
	
	static public function second_select_options($attributes = null, $selected = null) {
		if ($selected == null) {
			$selected = date('s');
		}
		return self::select_options(self::second_options(), $selected, $attributes);
	}
	
	static public function second_options() {
		$seconds = array();
		for($i = 0; $i < 60; $i ++) {
			if ($i < 10) {
				$key = "0$i";
			} else {
				$key = $i;
			}
			$seconds[$key] = $i;
		}
		return $seconds;
	}
	
	static public function datetime_selects($attributes = null, $format = null, $selected = null, $start = null, $end = null) {
		if ($format == null) {
			$format = 'Y/m/d G:i:s';
		}
		$return = '';
		for ($i = 0; $i < strlen($format); $i ++) {
			$select_attributes = $attributes;
			$f = substr($format, $i, 1);
			$select_attributes['name'] = $attributes['name']."_$f";
			$select_attributes['id'] = $attributes['id']."_$f";
			switch ($f) {
				case 'Y':
				case 'y':
					$select = self::year_select_options($start, $end, $select_attributes, $selected, $f);
					break;
				case 'm':
				case 'n':
					$select = self::month_select_options($select_attributes, $selected, $f);
					break;
				case 'd':
				case 'j':
					$select = self::day_select_options($select_attributes, $selected, $f);
					break;
				case 'G':
				case 'H':
					$select = self::hour_select_options($select_attributes, $selected, $f);
					break;
				case 'i':
					$select = self::minute_select_options($select_attributes, $selected);
					break;
				case 's':
					$select = self::second_select_options($select_attributes, $selected);
					break;
				default:
					$select = $f;
					break;
			}
			if (method_exists($select, 'render')) {
				$return .= $select->render();
			} else {
				$return .= $select;
			}
		}
		return $return;
	}
	
	static public function select_options($options, $selected = null, $attributes = null) {
		return new moojon_select_tag(self::options($options, $selected), $attributes);
	}
	
	static public function options($data, $selected = null) {
		if (is_array($data) == false) {
			$data = array($data);
		}
		$options = array();
		foreach ($data as $key => $value) {
			$attributes = array('value' => $value);
			if ($value == $selected) {
				$attributes['selected'] = 'selected';
			}
			$options[] = new moojon_option_tag($key, $attributes);
		}
		return $options;
	}
	
	static public function css_tag($file, $media = null, $path = null) {
		$file = moojon_files::strip_ext(basename($file), 'css');
		if ($media == null) {
			$media = 'screen, projection';
		}
		if ($path == null) {
			$path = '/'.moojon_config::get('css_directory');
		}
		return new moojon_link_tag(array('rel' => 'stylesheet', 'type' => 'text/css', 'href' => "$path/$file.css", 'media' => $media));
	}
	
	static public function js_tag($file, $path = null) {
		$file = moojon_files::strip_ext(basename($file), 'js');
		if ($path == null) {
			$path = '/'.moojon_config::get('js_directory');
		}
		return new moojon_script_tag(null, array('type' => 'text/javascript', 'src' => "$path/$file.js"));
	}
	
	static public function render_css_tags($files = null, $paths = null) {
		$return = '';
		if ($files == null) {
			foreach (moojon_assets::get_css() as $css) {
				$tag = self::css_tag($css);
				$return .= $tag->render()."\n";
			}
		} else {
			for ($i = 0; $i < (count($files) - 1); $i ++) {
				$tag = self::js_tag($files[$i], $paths[$i]);
				$return .= $tag->render()."\n";
			}
		}
		return $return;
	}
	
	static public function render_js_tags($files = null, $paths = null) {
		$return = '';
		if ($files == null) {
			foreach (moojon_assets::get_js() as $js) {
				$tag = self::js_tag($js);
				$return .= $tag->render()."\n";
			}
		} else {
			for ($i = 0; $i < (count($files) - 1); $i ++) {
				$tag = self::js_tag($files[$i], $paths[$i]);
				$return .= $tag->render()."\n";
			}
		}
		return $return;
	}
}
?>