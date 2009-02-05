<?php
final class moojon_generator extends moojon_base {	
	public function __construct() {}
	
	static public function run($template, $destination, $swaps) {
		if (!$handle = fopen($template, 'r')) {
			fclose($handle);
			self::handle_error("Unable to open template file for reading ($template)");
		}
		$template = fread($handle, filesize($template));
		fclose($handle);
		if (!$handle = fopen($destination, 'w')) {
			fclose($handle);
			self::handle_error("Unable to open destination file for writing ($destination)");
		}
		if (fwrite($handle, self::swap_out($template, $swaps, '<[', ']>')) === false) {
			fclose($handle);
			self::handle_error("Unable to write destination file ($destination)");
		}
		fclose($handle);
	}
	
	static public function swap_out($text, $swaps, $begin = null, $end = null) {
		if (!is_array($swaps)) {
			$swaps = array($swaps);
		}
		foreach ($swaps as $key => $value) {
			$text = str_replace("$begin$key$end", $value, $text);
		}
		return $text;
	}
	
	public function models() {
		foreach (moojon_adapter::list_tables() as $table) {
			$model = moojon_inflect::singularize($table);
			$swaps = array('model' => $model);
			$model_path = PROJECT_PATH."/models/$model.model.class.php";
			if (!file_exists($model_path)) {
				self::run('../moojon/'.MOOJON_VERSION.'/templates/model.template', $model_path, $swaps);
			}
			$swaps['columns'] = moojon_adapter::get_add_columns($table);
			self::run('../moojon/'.MOOJON_VERSION.'/templates/base.model.template', PROJECT_PATH."/models/base/base.$model.model.class.php", $swaps);
		}
	}
	
	public function migration($name) {
		$name = str_replace(' ', '_', $name);
		$filename = date('YmdHis').".$name.migration.class.php";
		self::run('../moojon/'.MOOJON_VERSION.'/templates/migration.template', PROJECT_PATH."/models/migrations/$filename", array('name' => $name));
	}
	
	public function project($name, $app = null) {}
	
	public function app($name, $layout = null) {}
	
	public function controller($name, $view = null) {}
	
	public function view($name) {}
	
	public function layout($name) {}
	
	public function test($name) {}
}
?>