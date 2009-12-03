<?php
function try_set_attribute($attributes, $key, $value) {
	if (!array_key_exists($key, $attributes)) {
		$attributes[$key] = $value;
	}
	return $attributes;
}

function title_text($column_name) {
	return ucfirst(str_replace('_', ' ', moojon_primary_key::get_table($column_name)));
}

function a_tag($content, $href, $attributes = array()) {
	$attributes['href'] = $href;
	return new moojon_a_tag($content, $attributes);
}

function img_tag($src, $alt = '', $width = null, $height = null, $attributes = array()) {
	$attributes = try_set_attribute($attributes, 'src', moojon_paths::get_public_image_path($src));
	$attributes = try_set_attribute($attributes, 'alt', $alt);
	$attributes = try_set_attribute($attributes, 'width', $width);
	$attributes = try_set_attribute($attributes, 'height', $height);
	return new moojon_img_tag($attributes);
}

function cancel_button($value = 'Cancel', $attributes = array()) {
	if (moojon_server::has('HTTP_REFERER')) {
		$attributes = try_set_attribute($attributes, 'class', 'cancel');
		return new moojon_a_tag($value, array('href' => moojon_server::get('HTTP_REFERER')));
	}
}

function back_button($value = 'Back', $attributes = array()) {
	if (moojon_server::has('HTTP_REFERER')) {
		$attributes = try_set_attribute($attributes, 'class', 'cancel');
		return new moojon_a_tag($value, array('href' => moojon_server::get('HTTP_REFERER')));
	}
}

function submit_tag($value) {
	return new moojon_input_tag(array('value' => $value, 'class' => 'submit', 'type' => 'image', 'name' => 'submit_tag', 'src' => '/'.moojon_config::get('images_directory').'/button_'.strtolower($value).'.'.moojon_config::get('default_image_ext'), 'id' => 'submit_'.strtolower($value)));
}

function method_tag($value) {
	return new moojon_input_tag(array('name' => moojon_config::get('method_key'), 'type' => 'hidden', 'value' => $value));
}

function redirection_tag($value) {
	return new moojon_input_tag(array('name' => moojon_config::get('redirection_key'), 'type' => 'hidden', 'value' => $value));
}

function year_select_options($start = null, $end = null, $attributes = null, $selected = null, $format = null) {
	if (!$selected) {
		$selected = time();
	}
	return select_options(year_options($start, $end, $format), date('Y', $selected), $attributes);
}

function year_options($start = null, $end = null, $format = null) {
	if ($format != 'Y' && $format != 'y') {
		$format = 'Y';
	}
	$return = array();
	if (!$start) {
		$start = abs(date('Y') - 50);
	}
	if (!$end) {
		$end = abs(date('Y') + 50);
	}
	for($i = min($start, $end); $i < (max($start, $end) + 1); $i ++) {
		$value = $i;
		if ($format == 'y') {
			$return[substr($value, -2)] = $value;
		} else {
			$return[$value] = $value;
		}
	}
	return $return;
}

function month_select_options($attributes = null, $selected = null, $format = null) {
	if (!$selected) {
		$selected = time();
	}
	return select_options(month_options($format), date('n', $selected), $attributes);
}

function month_options($format = null) {
	if ($format != 'm' && $format != 'n') {
		$format = 'm';
	}
	$return = array();
	for($i = 1; $i < 13; $i ++) {
		if ($i < 10 && $format == 'm') {
			$key = "0$i";
		} else {
			$key = $i;
		}
		$return[$key] = $i;
	}
	return $return;
}

function day_select_options($attributes = null, $selected = null, $format = null) {
	if (!$selected) {
		$selected = time();
	}
	return select_options(day_options($format), date('j', $selected), $attributes);
}

function day_options($format = null) {
	if ($format != 'd' && $format != 'j') {
		$format = 'd';
	}
	$return = array();
	for($i = 1; $i < 31; $i ++) {
		if ($i < 10 && $format == 'd') {
			$key = "0$i";
		} else {
			$key = $i;
		}
		$return[$key] = $i;
	}
	return $return;
}

function hour_select_options($attributes = null, $selected = null, $format = null) {
	if (!$selected) {
		$selected = time();
	}
	return select_options(hour_options($format), date('H', $selected), $attributes);
}

function hour_options($format = null) {
	if ($format != 'G' && $format != 'H') {
		$format = 'G';
	}
	$return = array();
	for($i = 0; $i < 24; $i ++) {
		if ($i < 10 && $format == 'G') {
			$key = "0$i";
		} else {
			$key = $i;
		}
		$return[$key] = $i;
	}
	return $return;
}

function minute_select_options($attributes = null, $selected = null) {
	if (!$selected) {
		$selected = time();
	}
	return select_options(minute_options(), date('i', $selected), $attributes);
}

function minute_options() {
	$return = array();
	for($i = 0; $i < 60; $i ++) {
		if ($i < 10) {
			$key = "0$i";
		} else {
			$key = $i;
		}
		$return[$key] = $i;
	}
	return $return;
}

function second_select_options($attributes = null, $selected = null) {
	if (!$selected) {
		$selected = time();
	}
	return select_options(second_options(), date('s', $selected), $attributes);
}

function second_options() {
	$return = array();
	for($i = 0; $i < 60; $i ++) {
		if ($i < 10) {
			$key = "0$i";
		} else {
			$key = $i;
		}
		$return[$key] = $i;
	}
	return $return;
}

function datetime_select_options($attributes = null, $format = null, $selected = null, $start = null, $end = null, $label = false) {
	if (!$format) {
		$format = moojon_config::get('datetime_format');
	}
	$return = new moojon_div_tag(null, array('class' => 'datetime'));
	$attributes = try_set_attribute($attributes, 'name', 'datetime_select');
	$attributes = try_set_attribute($attributes, 'id', $attributes['name']);
	for ($i = 0; $i < (strlen($format) + 1); $i ++) {
		$select = null;
		$select_attributes = $attributes;
		$f = substr($format, $i, 1);
		$select_attributes['name'] = $attributes['name']."[$f]";
		if ($i) {
			$select_attributes['id'] = $attributes['id']."_$f";
		} else {
			$select_attributes['id'] = $attributes['id'];
		}
		switch ($f) {
			case 'Y':
			case 'y':
				$select = year_select_options($start, $end, $select_attributes, $selected, $f);
				$label_text = 'Year';
				break;
			case 'm':
			case 'n':
				$select = month_select_options($select_attributes, $selected, $f);
				$label_text = 'Month';
				break;
			case 'd':
			case 'j':
				$select = day_select_options($select_attributes, $selected, $f);
				$label_text = 'Day';
				break;
			case 'G':
			case 'H':
				$select = hour_select_options($select_attributes, $selected, $f);
				$label_text = 'Hour';
				break;
			case 'i':
				$select = minute_select_options($select_attributes, $selected);
				$label_text = 'Minute';
				break;
			case 's':
				$select = second_select_options($select_attributes, $selected);
				$label_text = 'Second';
				break;
		}
		if ($select) {
			if ($label) {
				$return->add_child(new moojon_label_tag("$label_text:", array('for' => $select_attributes['id'])));
			}
			$return->add_child($select);
		}
	}
	return $return;
}

function datetime_label_select_options($attributes = null, $format = null, $selected = null, $start = null, $end = null) {
	return datetime_select_options($attributes, $format, $selected, $start, $end, true);
}

function select_options($options, $selected = null, $attributes = null) {
	return new moojon_select_tag(options($options, $selected), $attributes);
}

function options($data, $selected = null) {
	if (!is_array($data)) {
		$data = array($data);
	}
	$return = array();
	foreach ($data as $key => $value) {
		$attributes = array('value' => $value);
		if ($value == $selected) {
			$attributes['selected'] = 'selected';
		}
		$return[] = new moojon_option_tag($key, $attributes);
	}
	return $return;
}
?>