<?php
final class moojon_assets extends moojon_base {
	static private $instance;
	private $css = array();
	private $js = array();
	
	private function __construct() {
		$csss = array();
		foreach (explode(',', moojon_config::key('default_css')) as $css) {
			$csss[] = '/'.moojon_config::key('css_directory').'/'.moojon_files::require_ext(trim($css), 'css');
		}
		$this->css = self::prepare_additions($csss, 'css');
		$jss = array();
		foreach (explode(',', moojon_config::key('default_js')) as $js) {
			$jss[] = '/'.moojon_config::key('js_directory').'/'.moojon_files::require_ext(trim($js), 'js');
		}
		$this->js = self::prepare_additions($jss, 'js');
	}
	
	static public function get() {
		if (!self::$instance) {
			self::$instance = new moojon_assets();
		}
		return self::$instance;
	}
	
	static private function prepare_additions($additions, $ext) {
		$return = array();
		if (strlen($ext)) {
			$dot = '.';
		} else {
			$dot = '';
		}
		if (!is_array($additions)) {
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
		$ext = (!$dynamic) ? 'css' : '';
		foreach (self::prepare_additions($additions, $ext) as $css) {
			if (!in_array($css, $instance->css)) {
				$instance->css[] = $css;
			}
		}
	}
	
	static public function add_js($additions, $dynamic = false) {
		$instance = self::get();
		$ext = (!$dynamic) ? 'js' : '';
		foreach (self::prepare_additions($additions, $ext) as $js) {
			if (!in_array($js, $instance->js)) {
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