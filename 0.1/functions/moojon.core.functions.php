<?php
session_start();
function __autoload($class_name) {
	$class_filename = str_replace('_', '.', $class_name).'.class.php';
	if (file_exists(moojon_paths::get_classes_directory().$class_filename) == true) {
		require_once(moojon_paths::get_classes_directory().$class_filename);
	} elseif (file_exists(moojon_paths::get_adapter_directory().$class_filename) == true) {
		require_once(moojon_paths::get_adapter_directory().$class_filename);
	} elseif (file_exists(moojon_paths::get_columns_directory().str_replace('_', '.', $class_name).'.column.class.php') == true) {
		require_once(moojon_paths::get_columns_directory().str_replace('_', '.', $class_name).'.column.class.php');
	} elseif (file_exists(moojon_paths::get_columns_directory().$class_filename) == true) {
		require_once(moojon_paths::get_columns_directory().$class_filename);
	} elseif (file_exists(moojon_paths::get_validations_directory().$class_filename) == true) {
		require_once(moojon_paths::get_validations_directory().$class_filename);
	} elseif (file_exists(moojon_paths::get_tags_directory().$class_filename) == true) {
		require_once(moojon_paths::get_tags_directory().$class_filename);
	} elseif (file_exists(moojon_paths::get_tag_attributes_directory().$class_filename) == true) {
		require_once(moojon_paths::get_tag_attributes_directory().$class_filename);
	} elseif (file_exists(moojon_paths::get_moojon_models_directory().$class_filename) == true) {
		require_once(moojon_paths::get_moojon_models_directory().$class_filename);
	} elseif (file_exists(moojon_paths::get_moojon_base_models_directory().$class_filename) == true) {
		require_once(moojon_paths::get_moojon_base_models_directory().$class_filename);
	} elseif (file_exists(moojon_paths::get_vendor_directory().$class_filename) == true) {
		require_once(moojon_paths::get_vendor_directory().$class_filename);
	} elseif (file_exists(moojon_paths::get_library_directory().$class_filename) == true) {
		require_once(moojon_paths::get_library_directory().$class_filename);
	} elseif (file_exists(moojon_paths::get_models_directory().$class_name.'.model.class.php') == true) {
		require_once(moojon_paths::get_models_directory().$class_name.'.model.class.php');
	} elseif (file_exists(moojon_paths::get_base_models_directory().str_replace('base_', 'base.', $class_name).'.model.class.php') == true) {
		require_once(moojon_paths::get_base_models_directory().str_replace('base_', 'base.', $class_name).'.model.class.php');
	} else {
		//echo $class_name.' '.$class_filename.' '.moojon_paths::get_columns_directory().str_replace('_', '.', $class_name).'.column.class.php';
		//throw new moojon_exception("Not found ($class_filename)");
	}
}
?>