<?php
function image_tag($href, $alt, $width = null, $height = null, $attributes = array()) {
	if (array_key_exists('href', $attributes) == false) {
		$attributes['href'] = $href;
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