<?php
final class moojon_files extends moojon_base {
	
	private function __construct() {}
	
	static public function has($key) {
		if (is_array($_FILES) == false) {
			return false;
		}
		if (array_key_exists($key, $_FILES) === true) {
			if ($_FILES[$key] !== null) {
				return true;
			}
		}
		return false;
	}
	
	static public function set($key, $value = null) {
		if ($value !== null) {
			$_FILES[$key] = $value;
		} else {
			$_FILES[$key] = null;
			unset($_FILES[$key]);
		}
	}
	
	static public function clear() {
		if (is_array($_FILES) == true) {
			foreach($_FILES as $key) {
				$_FILES[$key] = null;
				unset($_FILES[$key]);
			}
		}
	}
	
	static public function key($key) {
		if (is_array($_FILES) == true) {
			if (array_key_exists($key, $_FILES) == true) {
				return $_FILES[$key];
			} else {
				throw new moojon_exception("Key does not exists in moojon_files ($key)");
			}
		} else {
			throw new moojon_exception("Key does not exists in moojon_files ($key)");
		}
	}
	
	static public function get_list() {
		if (is_array($_FILES) == true) {
			return $_FILES;
		} else {
			return array();
		}
	}
	
	static public function require_directory_files($path, $recursive = false) {
		foreach(self::directory_files($path, $recursive) as $file) {
			require_once($file);
		}
	}
	
	static public function require_files_then_require_directory_files($files, $path, $recursive = false) {
		if (is_array($files) == false) {
			$files = array($files);
		}
		foreach($files as $file) {
			require_once("$path/$file");
		}
		foreach(self::directory_files($path, $recursive) as $file) {
			if (!in_array($file, $files)) {
				require_once($file);
			}
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
		} else {
			throw new moojon_exception("Not a directory ($path)");
		}
		return $directories;
	}
	
	static public function directory_directories($path) {
		$directories = array();
		if (is_dir($path)) {
			if ($directory_handler = opendir($path)) {
				while (!(($directory = readdir($directory_handler)) === false)) {
					if (is_dir("$path$directory") && !self::parent_or_current($directory) && !self::mac_poo($directory)) {
						$directories[] = "$directory";
					}
				}
			}
			closedir($directory_handler);
		} else {
			throw new moojon_exception("Not a directory ($path)");
		}
		return $directories;
	}
	
	static private function parent_or_current($file) {
		if ($file == '.' || $file == '..') {
			return true;
		} else {
			return false;
		}
	}
	
	static private function mac_poo($file) {
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
	
	static private function has_suffix($file, $suffix) {
		if (substr($file, (0 + (strlen($file) - strlen($suffix)))) == $suffix) {
			return true;
		} else {
			return false;
		}
	}
	
	static public function require_suffix($path, $suffix, $ext = 'php') {
		$file = basename($path);
		$path = dirname($path).'/';
		if (self::has_ext($file, $ext) == true) {
			$file = self::strip_ext($file, $ext);
		}
		if (self::has_suffix($file, $suffix) == false) {
			$file .= ".$suffix";
		}
		if (strlen($ext) > 0) {
			$file .= ".$ext";
		}
		return $path.$file;
	}
	
	static private function has_prefix($file, $prefix) {
		if (substr($file, 0, strlen($prefix)) == $prefix) {
			return true;
		} else {
			return false;
		}
	}
	
	static public function require_prefix($path, $prefix, $ext = 'php') {
		$file = basename($path);
		$path = dirname($path).'/';
		if (self::has_ext($file, $ext) == true) {
			$file = self::strip_ext($file, $ext);
		}
		if (self::has_prefix($file, $prefix) == false) {
			$file .= ".$prefix";
		}
		if (strlen($ext) > 0) {
			$file .= ".$ext";
		}
		return $path.$file;
	}
	
	static public function get_file_contents($path) {
		$file_pointer = fopen($path, 'r');
		if ($file_pointer === false) {
			throw new moojon_exception("Unable to open file ($path)");
		}
		$content = fread($file_pointer, filesize($path));
		fclose($file_pointer);
		return $content;
	}
}
?>