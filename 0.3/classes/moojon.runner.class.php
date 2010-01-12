<?php
final class moojon_runner extends moojon_singleton {
	static protected $instance;
	static protected function factory($class) {if (!self::$instance) {self::$instance = new $class;}return self::$instance;}
	static public function fetch() {return self::factory(get_class());}
	
	protected function __construct() {
		require_once(MOOJON_DIRECTORY.'/classes/moojon.exception.class.php');
		require_once(MOOJON_DIRECTORY.'/classes/moojon.config.class.php');
		require_once(MOOJON_DIRECTORY.'/classes/moojon.files.class.php');
		require_once(MOOJON_DIRECTORY.'/classes/moojon.paths.class.php');
		require_once(MOOJON_DIRECTORY.'/classes/moojon.base.cli.class.php');
		require_once(MOOJON_DIRECTORY.'/classes/moojon.cli.class.php');
		require_once(MOOJON_DIRECTORY.'/functions/moojon.core.functions.php');
		switch (strtoupper(UI)) {
			case 'CGI':
				ini_set("memory_limit","256M");
				moojon_session::fetch();
				moojon_uri::fetch();
				$uri = moojon_uri::get_uri();
				moojon_config::update(moojon_paths::get_project_app_config_directory(APP));
				/*$path = moojon_paths::get_cache_path($uri);
				$from_cache = false;
				if (moojon_cache::get_enabled() && moojon_cache::expired($uri)) {
					moojon_files::put_file_contents($path, self::render_app());
				}*/
				//echo ($from_cache) ? moojon_files::get_file_contents($path) : self::render_app();
				echo self::render_app();
				break;
			case 'CLI':
				$cli_class = CLI;
				new $cli_class;
				break;
			default:
				throw new moojon_exception('Invalid UI ('.UI.')');
				break;
		}
	}
	
	static private function render_app() {
		self::require_view_functions();
		require_once(moojon_paths::get_app_path(APP));
		$app_class = self::get_app_class(APP);
		$app = new $app_class(moojon_uri::get_uri());
		return $app->render();
	}
	
	static public function render($path, moojon_base $object = null) {
		if ($object) {
			foreach (get_object_vars($object) as $key => $value) {
				$$key = $value;
			}
		}
		ob_start();
		require_once($path);
		$return = ob_get_clean();
		if (ob_get_length()) {
			ob_end_clean();
		}
		return $return;
	}
}
?>