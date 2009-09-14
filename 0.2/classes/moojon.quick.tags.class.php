<?php
final class moojon_quick_tags extends moojon_base {
	private function __construct() {}
	
	static public function link_to($text, $action, $controller = null, $app = null, $attributes = array()) {
		if (!array_key_exists('href', $attributes)) {
			if (!$controller) {
				$controller = CONTROLLER;
			}
			if (!$app) {
				$app = APP;
			}
			$attributes['href'] = moojon_config::key('index_file')."$app/$controller/$action";
		}
		return new moojon_a_tag($text, $attributes);
	}
	
	static public function year_select_options($start = null, $end = null, $attributes = null, $selected = null, $format = null) {
		if (!$selected) {
			$selected = time();
		}
		return self::select_options(self::year_options($start, $end, $format), date('Y', $selected), $attributes);
	}
	
	static public function year_options($start = null, $end = null, $format = null) {
		if ($format != 'Y' && $format != 'y') {
			$format = 'Y';
		}
		$years = array();
		if (!$start) {
			$start = abs(date('Y') - 50);
		}
		if (!$end) {
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
		if (!$selected) {
			$selected = time();
		}
		return self::select_options(self::month_options($format), date('n', $selected), $attributes);
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
		if (!$selected) {
			$selected = time();
		}
		return self::select_options(self::day_options($format), date('j', $selected), $attributes);
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
		if (!$selected) {
			$selected = time();
		}
		return self::select_options(self::hour_options($format), date('H', $selected), $attributes);
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
		if (!$selected) {
			$selected = time();
		}
		return self::select_options(self::minute_options(), date('i', $selected), $attributes);
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
		if (!$selected) {
			$selected = time();
		}
		return self::select_options(self::second_options(), date('s', $selected), $attributes);
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
	
	static private function datetime_label($text, $attributes) {
		$label_attributes = array();
		$label_attributes['id'] = $attributes['id'].'_label';
		$label_attributes['for'] = $attributes['id'];
		return new moojon_label_tag($text, $label_attributes);
	}
	
	static public function datetime_select_options($attributes = null, $format = null, $selected = null, $start = null, $end = null, $label = false) {
		if (!$format) {
			$format = (moojon_config::has('db_driver')) ? moojon_db_driver::get_datetime_format(): 'Y/m/d H:i:s';
		}
		$return = '';
		if (!array_key_exists('name', $attributes)) {
			$attributes['name'] = 'datetime_select';
		}
		if (!array_key_exists('id', $attributes)) {
			$attributes['id'] = $attributes['name'];
		}
		for ($i = 0; $i < (strlen($format) + 1); $i ++) {
			$select = null;
			$select_attributes = $attributes;
			$f = substr($format, $i, 1);
			$select_attributes['name'] = $attributes['name']."[$f]";
			$select_attributes['id'] = $attributes['id']."_$f";
			switch ($f) {
				case 'Y':
				case 'y':
					$select = self::year_select_options($start, $end, $select_attributes, $selected, $f);
					$label_text = 'Year';
					break;
				case 'm':
				case 'n':
					$select = self::month_select_options($select_attributes, $selected, $f);
					$label_text = 'Month';
					break;
				case 'd':
				case 'j':
					$select = self::day_select_options($select_attributes, $selected, $f);
					$label_text = 'Day';
					break;
				case 'G':
				case 'H':
					$select = self::hour_select_options($select_attributes, $selected, $f);
					$label_text = 'Hour';
					break;
				case 'i':
					$select = self::minute_select_options($select_attributes, $selected);
					$label_text = 'Minute';
					break;
				case 's':
					$select = self::second_select_options($select_attributes, $selected);
					$label_text = 'Second';
					break;
			}
			if ($select) {
				if ($label) {
					$datetime_label = self::datetime_label("$label_text:", $select_attributes);
					$return .= $datetime_label->render();
				}
				$return .= $select->render();
			}
		}
		return $return;
	}
	
	static public function datetime_label_select_options($attributes = null, $format = null, $selected = null, $start = null, $end = null) {
		return self::datetime_select_options($attributes, $format, $selected, $start, $end, true);
	}
	
	static public function select_options($options, $selected = null, $attributes = null) {
		return new moojon_select_tag(self::options($options, $selected), $attributes);
	}
	
	static public function options($data, $selected = null) {
		if (!is_array($data)) {
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
	
	static public function css_tag($path, $media = null) {
		if (!$media) {
			$media = 'screen, projection';
		}
		return new moojon_link_tag(array('rel' => 'stylesheet', 'type' => 'text/css', 'href' => "$path", 'media' => $media));
	}
	
	static public function js_tag($path) {
		return new moojon_script_tag(null, array('type' => 'text/javascript', 'src' => $path));
	}
	
	static public function render_css_tags($media = null) {
		$return = '';
		foreach (moojon_assets::get_css() as $css) {
			$tag = self::css_tag($css, $media);
			$return .= $tag->render()."\n";
		}
		return $return;
	}
	
	static public function render_js_tags() {
		$return = '';
		foreach (moojon_assets::get_js() as $js) {
			$tag = self::js_tag($js);
			$return .= $tag->render()."\n";
		}
		return $return;
	}
}
?>