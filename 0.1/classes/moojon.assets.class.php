<?php
final class moojon_assets extends moojon_base {
	static private $instance;
	static private $css = array();
	static private $js = array();
	
	private function __construct() {
		$this->css = self::prepare_additions(moojon_config::get('default_css'), 'css');
		$this->js = self::prepare_additions(moojon_config::get('default_js'), 'js');
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
		if (is_array($additions) == false) {
			foreach (explode(',', $additions) as $addition) {
				$return[] = moojon_files::strip_ext(basename(trim($addition)), $ext);
			}
		} else {
			foreach ($additions as $addition) {
				$return[] = moojon_files::strip_ext(basename(trim($addition)), $ext);
			}
		}
		return $return;
	}
	
	static public function add_css($additions) {
		$instance = self::get();
		foreach (self::prepare_additions($additions, 'css') as $css) {
			$instance->css[] = $css;
		}
	}
	
	static public function add_js($additions) {
		$instance = self::get();
		foreach (self::prepare_additions($additions, 'js') as $js) {
			$instance->js[] = $js;
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