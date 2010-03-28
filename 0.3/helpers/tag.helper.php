<?php
function validate_email($email) {
	return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
}

function process_uri($uri) {
	return moojon_server::process_uri($uri);
}

function img_uri($uri) {
	$uri_segments = parse_url($uri);
	$path_segments = pathinfo($uri_segments['path']);
	if (!array_key_exists('extension', $path_segments)) {
		$path_segments['extension'] = moojon_config::get('default_image_ext');
	}
	if (!array_key_exists('dirname', $path_segments) || $path_segments['dirname'] == '.') {
		$path_segments['dirname'] = moojon_paths::get_public_images_directory();
	}
	if (substr($path_segments['dirname'], -1) == '/') {
		$path_segments['dirname'] = substr($path_segments['dirname'], 0, -1);
	}
	$uri_segments['path'] = $path_segments['dirname'].'/'.$path_segments['filename'].'.'.$path_segments['extension'];
	return process_uri($uri_segments['path']);
}

function vet_tag_type($type, $parent) {
	$type = str_replace('moojon_', '', str_replace('_tag', '', $type));
	$type = "moojon_$type".'_tag';
	$parent = str_replace('moojon_base_', '', str_replace('_tag', '', $parent));
	$parent = "moojon_base_$parent".'_tag';
	if (is_subclass_of($type, $parent)) {
		return $type;
	} else {
		throw new moojon_exception("Tag class mismatch $type, $parent");
	}
}

function vet_datetime_selected($selected = null) {
	if (!$selected) {
		$selected = time();
	}
	if (!is_int($selected)) {
		$selected = (string)$selected;
		$selected = date_parse($selected);
		$selected = mktime($selected['hour'], $selected['minute'], $selected['second'], $selected['month'], $selected['day'], $selected['year']);
	}
	return $selected;
}

function open_tag($type, $content, $attributes = array()) {
	$type = vet_tag_type($type, 'open');
	return new $type($content, $attributes);
}

function empty_tag($type, $attributes = array()) {
	$type = vet_tag_type($type, 'empty');
	return new $type($attributes);
}

function try_set_attribute($attributes, $key, $value) {
	if (!array_key_exists($key, $attributes)) {
		$attributes[$key] = $value;
	}
	return $attributes;
}

function attribute_array_name($array_name, $element_name) {
	return $array_name."[$element_name]";
}

function add_class(moojon_base_tag $tag, $class) {
	$tag->add_class($class);
	return $tag;
}

function title_text($column_name) {
	return ucfirst(str_replace('_', ' ', moojon_primary_key::get_class($column_name)));
}

function a_tag($content, $href, $attributes = array()) {
	$attributes['href'] = process_uri($href);
	return open_tag('a', $content, $attributes);
}

function mailto_tag($content, $href = null, $attributes = array()) {
	$href = ($href) ? $href : $content;
	$href = (substr($href, 0, 6) == 'mailto') ? substr($href, 6) : $href;
	$href = (substr($href, 0, 1) == ':') ? substr($href, 1) : $href;
	if (validate_email($href)) {
		$attributes = try_set_attribute($attributes, 'href', "mailto:$href");
	}
	return open_tag('a', $content, $attributes);
}

function h1_tag($content = null, $attributes = array()) {
	return open_tag('h1', $content, $attributes);
}

function h2_tag($content = null, $attributes = array()) {
	return open_tag('h2', $content, $attributes);
}

function h3_tag($content = null, $attributes = array()) {
	return open_tag('h3', $content, $attributes);
}

function h4_tag($content = null, $attributes = array()) {
	return open_tag('h4', $content, $attributes);
}

function h5_tag($content = null, $attributes = array()) {
	return open_tag('h5', $content, $attributes);
}

function h6_tag($content = null, $attributes = array()) {
	return open_tag('h6', $content, $attributes);
}

function img_tag($src, $alt = '', $width = null, $height = null, $attributes = array()) {
	$attributes = try_set_attribute($attributes, 'src', img_uri($src));
	$attributes = try_set_attribute($attributes, 'alt', $alt);
	$attributes = try_set_attribute($attributes, 'width', $width);
	$attributes = try_set_attribute($attributes, 'height', $height);
	return empty_tag('img', $attributes);
}

function div_tag($content = null, $attributes = array()) {
	return open_tag('div', $content, $attributes);
}

function p_tag($content = null, $attributes = array()) {
	return open_tag('p', $content, $attributes);
}

function strong_tag($content = null, $attributes = array()) {
	return open_tag('strong', $content, $attributes);
}

function ul_tag($content = null, $attributes = array()) {
	return open_tag('ul', $content, $attributes);
}

function li_tag($content = null, $attributes = array()) {
	return open_tag('li', $content, $attributes);
}

function dl_tag($content = null, $attributes = array()) {
	return open_tag('dl', $content, $attributes);
}

function dt_tag($content = null, $attributes = array()) {
	return open_tag('dt', $content, $attributes);
}

function dd_tag($content = null, $attributes = array()) {
	return open_tag('dd', $content, $attributes);
}

function table_tag($content = null, $attributes = array()) {
	return open_tag('table', $content, $attributes);
}

function thead_tag($content = null, $attributes = array()) {
	return open_tag('thead', $content, $attributes);
}

function tbody_tag($content = null, $attributes = array()) {
	return open_tag('tbody', $content, $attributes);
}

function tfoot_tag($content = null, $attributes = array()) {
	return open_tag('tfoot', $content, $attributes);
}

function tr_tag($content = null, $attributes = array()) {
	return open_tag('tr', $content, $attributes);
}

function th_tag($content = null, $attributes = array()) {
	return open_tag('th', $content, $attributes);
}

function td_tag($content = null, $attributes = array()) {
	return open_tag('td', $content, $attributes);
}

function form_tag($content = null, $attributes = array()) {
	return open_tag('form', $content, $attributes);
}

function fieldset_tag($content = null, $attributes = array()) {
	return open_tag('fieldset', $content, $attributes);
}

function label_tag($content = null, $for = null, $attributes = array()) {
	$attributes = try_set_attribute($attributes, 'for', $for);
	return open_tag('label', $content, $attributes);
}

function input_tag($attributes = array()) {
	return empty_tag('input', $attributes);
}

function select_tag($content, $attributes = array()) {
	return open_tag('select', $content, $attributes);
}

function option_tag($content, $attributes = array()) {
	return open_tag('option', $content, $attributes);
}

function textarea_tag($content, $attributes = array()) {
	return open_tag('textarea', $content, $attributes);
}

function input_tag_type($type, $attributes = array()) {
	$attributes = try_set_attribute($attributes, 'type', $type);
	$attributes = try_set_attribute($attributes, 'class', $type);
	return input_tag($attributes);
}

function text_input_tag($attributes = array()) {
	return input_tag_type('text', $attributes);
}

function password_input_tag($attributes = array()) {
	return input_tag_type('password', $attributes);
}

function checkbox_input_tag($attributes = array()) {
	return input_tag_type('checkbox', $attributes);
}

function hidden_input_tag($attributes = array()) {
	return input_tag_type('hidden', $attributes);
}

function file_input_tag($attributes = array()) {
	return input_tag_type('file', $attributes);
}

function submit_input_tag($attributes = array()) {
	return input_tag_type('submit', $attributes);
}

function image_input_tag($src, $attributes = array()) {
	$attributes = try_set_attribute($attributes, 'src', img_uri($src));
	return input_tag_type('image', $attributes);
}

function login_form($authenticated = false, $message = null, $redirect = null, $attributes = array()) {
	if (!$authenticated) {
		$security_identity_label = moojon_config::get('security_identity_label');
		$security_password_label = moojon_config::get('security_password_label');
		$security_remember_label = moojon_config::get('security_remember_label');
		$security_key = moojon_config::get('security_key');
		$security_identity_key = moojon_config::get('security_identity_key');
		$security_password_key = moojon_config::get('security_password_key');
		$security_remember_key = moojon_config::get('security_remember_key');
		if (moojon_security::login_attempt($security_key)) {
			$security = moojon_request::get($security_key);
			$security_identity_value = $security[$security_identity_key];
			$security_password_value = $security[$security_password_key];
			$security_remember_value = $security[$security_remember_key];
		} else {
			$security_remember_value = null;
			$security_identity_value = null;
			$security_password_value = null;
			$security_checked_value = null;
		}
		$child = form_tag(null, array('action' => '#', 'method' => 'post'));
		if ($redirect) {
			$child->add_child(redirection_tag($redirect));
		}
		if ($message) {
			$child->add_child(p_tag($message, array('class' => 'error')));
		}
		$fieldset = fieldset_tag();
		$fieldset->add_child(label_tag($security_identity_label, $security_identity_key));
		$fieldset->add_child(text_input_tag(array('id' => $security_identity_key, 'name' => attribute_array_name($security_key, $security_identity_key), 'value' => $security_identity_value)));
		$fieldset->add_child(label_tag($security_password_label, $security_password_key));
		$fieldset->add_child(password_input_tag(array('id' => $security_password_key, 'name' => attribute_array_name($security_key, $security_password_key),  'value' => $security_password_value)));
		$div = div_tag(null, array('class' => 'checkbox'));
		$div->add_child(hidden_input_tag(array('name' => attribute_array_name($security_key, $security_remember_key),  'value' => 0)));
		$remember_attributes = array('name' => attribute_array_name($security_key, $security_remember_key),  'value' => 1, 'id' => $security_remember_key);
		if ($security_remember_value) {
			$remember_attributes['checked'] = 'checked';
		}
		$div->add_child(checkbox_input_tag($remember_attributes));
		$div->add_child(label_tag($security_remember_label, $security_remember_key));
		$fieldset->add_child($div);
		$child->add_child($fieldset);
		$child->add_child(submit_input_tag(array('name' => attribute_array_name($security_key, 'submit'), 'value' => 'Login')));
	} else {
		$child = p_tag('You are logged in.');
	}
	return div_tag($child, array('id' => 'login_div'));
}

function error_dl($message, $errors = array(), $attributes = array()) {
	$children = array();
	$attributes = try_set_attribute($attributes, 'class', 'errors');
	if (count($errors)) {
		$children[] = dt_tag($message);
		foreach ($errors as $key => $value) {
			$children[] = dd_tag(label_tag($value, $key));
		}
		$return = dl_tag($children, $attributes);
	} else {
		$return = div_tag($children, $attributes);
	}
	return $return;
}

function paginator_ul($records, $limit = null, $page_symbol_name = null, $limit_symbol_name = null, $attributes = array()) {
	if (!is_integer($records)) {
		$records = count($records);
	}
	$limit_symbol_name = ($limit_symbol_name) ? $limit_symbol_name : moojon_config::get('paginator_limit_symbol_name');
	if (!$limit) {
		$limit = (moojon_uri::has($limit_symbol_name)) ? moojon_uri::get($limit_symbol_name) : moojon_config::get('paginator_limit');
	}
	if ($records > $limit) {
		$page_symbol_name = ($page_symbol_name) ? $page_symbol_name : moojon_config::get('paginator_page_symbol_name');
		$page = (moojon_uri::has($page_symbol_name)) ? moojon_uri::get($page_symbol_name) : 1;
		$page = ($page < 1) ? 1 : (int)$page;
		$params = array();
		foreach (moojon_uri::get_data() as $key => $value) {
			if (is_string($value)) {
				$params[$key] = $value;
			}
		}
		$match_pattern = moojon_uri::get_match_pattern();
		$match_pattern = (substr($match_pattern, -1) != '/') ? "$match_pattern/" : $match_pattern;
		if (!array_key_exists($page_symbol_name, $params)) {
			$match_pattern .= "$page_symbol_name/:$page_symbol_name/";
		}
		$match_pattern = moojon_config::get('index_file').$match_pattern;
		$params[$limit_symbol_name] = $limit;
		$children = array();
		for ($i = 1; $i < (ceil($records / $limit) + 1); $i ++) {
			$params[$page_symbol_name] = $i;
			$attributes = ($i == $page) ? array('class' => 'selected') : array();
			$children[] = li_tag(a_tag($i, moojon_base::parse_symbols($match_pattern, $params), $attributes));
		}
		$return = ul_tag($children);
	} else {
		$return = '';
	}
	return $return;
}

function cancel_button($value = 'Cancel', $attributes = array()) {
	if (moojon_server::has('HTTP_REFERER')) {
		$attributes = try_set_attribute($attributes, 'class', 'cancel');
		return a_tag($value, array('href' => moojon_server::get('HTTP_REFERER')));
	}
}

function back_button($value = 'Back', $attributes = array()) {
	if (moojon_server::has('HTTP_REFERER')) {
		$attributes = try_set_attribute($attributes, 'class', 'cancel');
		return a_tag($value, array('href' => moojon_server::get('HTTP_REFERER')));
	}
}

function method_tag($value) {
	return hidden_input_tag(array('name' => moojon_config::get('method_key'), 'value' => $value));
}

function redirection_tag($value) {
	return hidden_input_tag(array('name' => moojon_config::get('redirection_key'), 'value' => $value));
}

function year_select_options($start = null, $end = null, $attributes = null, $selected = null, $format = null) {
	$selected = vet_datetime_selected($selected);
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
	$selected = vet_datetime_selected($selected);
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
	$selected = vet_datetime_selected($selected);
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
	$selected = vet_datetime_selected($selected);
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
	$selected = vet_datetime_selected($selected);
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
	$selected = vet_datetime_selected($selected);
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
	$return = div_tag(null, array('class' => 'datetime'));
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
				$return->add_child(label_tag("$label_text:", $select_attributes['id']));
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
	return select_tag(options($options, $selected), $attributes);
}

function select_numeric_options($start, $end, $selected = null, $attributes = null) {
	return select_tag(numeric_options($start, $end, $selected), $attributes);
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
		$return[] = option_tag($key, $attributes);
	}
	return $return;
}

function numeric_options($start, $end, $selected = null) {
	$options = array();
	for ($i = $start; $i < ($end + 1); $i ++) {
		$options[$i] = $i;
	}
	return options($options, $selected);
}
?>