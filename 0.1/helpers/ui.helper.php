<?php
function img_tag($src, $alt, $width = null, $height = null, $attributes = array()) {
	echo "$src<br />";
	$url = parse_url($src);
	print_r($url);
	die();
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