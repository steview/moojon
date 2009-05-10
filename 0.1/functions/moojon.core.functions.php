<?php
session_start();
function __autoload($class_name) {
	$class_filename = str_replace('_', '.', $class_name).'.class.php';
	if (file_exists(moojon_paths::get_moojon_classes_directory().$class_filename) == true) {
		require_once(moojon_paths::get_moojon_classes_directory().$class_filename);
	} elseif (file_exists(moojon_paths::get_adapter_directory().$class_filename) == true) {
		require_once(moojon_paths::get_adapter_directory().$class_filename);
	} elseif (file_exists(moojon_paths::get_columns_directory().str_replace('_', '.', $class_name).'.column.class.php') == true) {
		require_once(moojon_paths::get_columns_directory().str_replace('_', '.', $class_name).'.column.class.php');
	} elseif (file_exists(moojon_paths::get_moojon_validations_directory().$class_filename) == true) {
		require_once(moojon_paths::get_moojon_validations_directory().$class_filename);
	} elseif (file_exists(moojon_paths::get_moojon_tags_directory().$class_filename) == true) {
		require_once(moojon_paths::get_moojon_tags_directory().$class_filename);
	} elseif (file_exists(moojon_paths::get_moojon_tag_attributes_directory().$class_filename) == true) {
		require_once(moojon_paths::get_moojon_tag_attributes_directory().$class_filename);
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
		echo "not found $class_name $class_filename ".moojon_paths::get_columns_directory().str_replace('_', '.', $class_name).'.column.class.php'."<br />";
		//die(moojon_paths::get_columns_directory().str_replace('_', '.', $class_name).'.column.class.php');
		//moojon_base::handle_error("$class_name not found as a library or vendor item.");
	}
}
?>