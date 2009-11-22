<?php
final class image_resizer extends moojon_base {
	private function __construct() {}
	
	static public function resize_to_width($origin, $new_width, $destination = null, $compression = null) {
		$destination = ($destination) ? $destination : $origin;
		$image_information = getimagesize($origin);
		$width = $image_information[0];
		$height = $image_information[1];
		$type = $image_information[2];
		$source = self::create_from($origin, $type);
		$new_height = ($height / $width) * $new_width;
		$resource = imagecreatetruecolor($new_width, $new_height);
		imagecopyresampled($resource, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		self::save($resource, $type, $destination, $compression);
	}
	
	static public function resize_to_height($origin, $new_height, $destination = null, $compression = null) {
		$destination = ($destination) ? $destination : $origin;
		$image_information = getimagesize($origin);
		$width = $image_information[0];
		$height = $image_information[1];
		$type = $image_information[2];
		$source = self::create_from($origin, $type);
		$new_width = ($width / $height) * $new_height;
		$resource = imagecreatetruecolor($new_width, $new_height);
		imagecopyresampled($resource, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		self::save($resource, $image_information[2], $destination, $compression);
	}
	
	static private function create_from($origin, $type) {
		switch ($type) {
			case IMAGETYPE_GIF:
				return imagecreatefromgif($origin);
				break;
			case IMAGETYPE_JPEG:
				return imagecreatefromjpeg($origin);
				break;
			case IMAGETYPE_PNG:
				return imagecreatefrompng($origin);
				break;
		}
	}
	
	static private function save($resource, $type, $destination, $compression = null) {
		self::try_mkdir(dirname($destination));
		if (file_exists($destination)) {
			unlink($destination);
		}
		switch ($type) {
			case IMAGETYPE_GIF:
				imagegif($resource, $destination);
				break;
			case IMAGETYPE_JPEG:
				$compression = ($compression) ? $compression : 85;
				imagejpeg($resource, $destination, $compression);
				break;
			case IMAGETYPE_PNG:
				$compression = ($compression) ? $compression : 8;
				imagepng($resource, $destination, $compression);
				break;
		}
	}
}
?>