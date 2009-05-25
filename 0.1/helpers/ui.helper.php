<?php
function img_tag($src, $alt, $width = null, $height = null, $attributes = array()) {
	$url = parse_url($src);
	$host = (array_key_exists('host', $url)) ? $url['host'] : '';
	$path = $url['path'];
	if (substr($path, 0, 1) != '/') {
		$path = '/'.$path;
	}
	if (array_key_exists('host', $url)) {
		$host = $url['host'];
		$scheme = $url['scheme'];
	} else {
		$host = moojon_server::key('HTTP_HOST');
		if (!file_exists(moojon_paths::get_public_directory().$path)) {
			if (file_exists(moojon_paths::get_public_directory().$path.'.'.moojon_config::get('default_image_ext'))) {
				$path .= '.'.moojon_config::get('default_image_ext');
			} else {
				if (file_exists(moojon_paths::get_images_directory().$path)) {
					$path = '/'.moojon_config::get('images_directory').$path;
				} else {
					if (file_exists(moojon_paths::get_images_directory().$path.'.'.moojon_config::get('default_image_ext'))) {
						$path = '/'.moojon_config::get('images_directory').$path.'.'.moojon_config::get('default_image_ext');
					}
				}
			}
		}
	}
	if (moojon_server::key('SERVER_PROTOCOL') == 'HTTP/1.1') {
		$scheme = 'http';
	} else {
		$scheme = 'https';
	}
	$attributes['src'] = "$scheme://$host$path";
	$attributes['alt'] = $alt;
	if (!array_key_exists('width', $attributes)) {
		$attributes['width'] = $width;
	}
	if (!array_key_exists('height', $attributes)) {
		$attributes['height'] = $height;
	}
	$tag = new moojon_img_tag($attributes);
	return $tag->render();
}
?>