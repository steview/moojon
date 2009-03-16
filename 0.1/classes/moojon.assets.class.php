<?php
final class moojon_assets extends moojon_base {
	static private $instance;
	static private $css = array();
	static private $js = array();
	
	private function __construct() {
		$csss = array();
		foreach (explode(',', moojon_config::get('default_css')) as $css) {
			$csss[] = '/'.moojon_config::get('css_directory').'/'.moojon_files::require_ext(trim($css), 'css');
		}
		$this->css = self::prepare_additions($csss, 'css');
		$jss = array();
		foreach (explode(',', moojon_config::get('default_js')) as $js) {
			$jss[] = '/'.moojon_config::get('js_directory').'/'.moojon_files::require_ext(trim($js), 'js');
		}
		$this->js = self::prepare_additions($jss, 'js');
	}
	
	static public function get($key = null) {
		if (!self::$instance) {
			self::$instance = new moojon_assets();
		}
		if ($key == null) {
			return self::$instance;
		} else {
			return self::$instance->$key;
		}
	}
	
	static private function prepare_additions($additions, $ext) {
		$return = array();
		if (strlen($ext) > 0) {
			$dot = '.';
		} else {
			$dot = '';
		}
		if (is_array($additions) == false) {
			foreach (explode(',', $additions) as $addition) {
				$return[] = moojon_files::strip_ext(trim($addition), $ext).$dot.$ext;
			}
		} else {
			foreach ($additions as $addition) {
				$return[] = moojon_files::strip_ext(trim($addition), $ext).$dot.$ext;
			}
		}
		return $return;
	}
	
	static public function add_css($additions, $dynamic = false) {
		$instance = self::get();
		$ext = ($dynamic === false) ? 'css' : '';
		foreach (self::prepare_additions($additions, $ext) as $css) {
			if (in_array($css, $instance->css) === false) {
				$instance->css[] = $css;
			}
		}
	}
	
	static public function add_js($additions, $dynamic = false) {
		$instance = self::get();
		$ext = ($dynamic === false) ? 'js' : '';
		foreach (self::prepare_additions($additions, $ext) as $js) {
			if (in_array($js, $instance->js) === false) {
				$instance->js[] = $js;
			}
		}
	}
	
	static public function get_css() {
		$instance = self::get();
		return $instance->css;
	}
	
	static public function get_js() {
		$instance = self::get();
		return $instance->js;
	}
}
?>