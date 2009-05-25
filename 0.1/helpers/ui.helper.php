<?php
function img_tag($src, $alt, $width = null, $height = null, $attributes = array()) {
	$url = parse_url($src);
	$host = (array_key_exists('host', $url)) ? $url['host'] : '';
	if (array_key_exists('host', $url) == ture) {
		$host = $url['host'];
		$scheme = $url['scheme'];
		$path = $url['path'];
	} else {
		$host = moojon_server::get('HTTP_HOST');
		if (strpos(strtolower(moojon_server::get('SERVER_PROTOCOL')), 'http/') !== false) {
			$scheme = 'https';
		} else {
			$scheme = 'http';
		}
		if (file_exists($url['path']) === false) {
			
		}
	}
 	if (array_key_exists('src', $attributes) == false) {
		$attributes['src'] = $src;
	}
	if (array_key_exists('alt', $attributes) == false) {
		$attributes['alt'] = $alt;
	}
	if (array_key_exists('width', $attributes) == false) {
		$attributes['width'] = $width;
	}
	if (array_key_exists('height', $attributes) == false) {
		$attributes['height'] = $height;
	}
	$tag = new moojon_img_tag($attributes);
	return $tag->render();
}
?>