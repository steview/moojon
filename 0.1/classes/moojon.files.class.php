<?php
class moojon_files {
	private $app_path;
	private $app_class_path;
	private $controllers_path;
	private $controller_class_path;
	private $views_path;
	private $view_path;
	
	static public function require_models($config) {
		self::require_directory_files(PROJECT_PATH.$config->models_directory.'/'.$config->generated_models_directory.'/');
		self::require_directory_files(PROJECT_PATH.$config->models_directory.'/');
	}
		
	static public function require_directory_files($path, $recursive = false) {
		foreach(self::directory_files($path, $recursive) as $file) {
			require_once($file);
		}
	}
	
	static public function directory_files($path, $recursive = false) {
		$directories = array();
		if (is_dir($path)) {
			if ($directory_handler = opendir($path)) {
				while (!(($file = readdir($directory_handler)) === false)) {
					if (is_dir("$path$file") && !self::parent_or_current($file) && $recursive) {
						self::require_directory_files("$path$file/");
					} elseif (!self::mac_poo($file) && self::has_ext($file)) {
						$directories[] = "$path$file";
					}
				}
			}
			closedir($directory_handler);
		}
		return $directories;
	}
	
	static public function parent_or_current($file) {
		if ($file == '.' || $file == '..') {
			return true;
		} else {
			return false;
		}
	}
	
	static public function mac_poo($file) {
		if (substr($file, 0, 2) == '._') {
			return true;
		} else {
			return false;
		}
	}
	
	static public function has_ext($file, $ext = 'php') {
		if (self::get_ext($file) == $ext) {
			return true;
		} else {
			return false;
		}
	}
	
	static public function strip_ext($file, $ext = 'php') {
		if (self::has_ext($file, $ext)) {
			return substr($file, 0, (strlen($file) - strlen(".$ext")));
		} else {
			return $file;
		}
	}
	
	static public function get_ext($file) {
		if (strpos($file, '.') > 0) {
			return substr($file, (strrpos($file, '.') + 1));
		} else {
			return false;
		}
	}
	
	static public function require_ext($file, $ext) {
		if (!self::has_ext($file, $ext)) {
			return "$file.$ext";
		} else {
			return $file;
		}
	}
	
	static public function has_suffix($file, $suffix) {
		if (substr($file, (0 + (strlen($file) - strlen($suffix)))) == $suffix) {
			return true;
		} else {
			return false;
		}
	}
	
	static public function require_suffix($file, $suffix) {
		if (!$ext = self::get_ext($file)) {
			$file = self::strip_ext($file, $ext);
		} else {
			$ext = '';
		}
		$suffix = self::strip_ext($suffix, $ext);
		if (self::has_suffix($file, $suffix)) {
			return "$file.$ext";
		} else {
			return "$file.$suffix.$ext";
		}
		
	}
}
?>