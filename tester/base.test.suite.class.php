<?php
abstract class base_test_suite {
	private $failure = null;
	
	final public function __construct() {
		$this->set_up();
	}
	
	protected function set_up() {}
	
	final public function __destruct() {
		$this->tear_down();
	}
	
	protected function tear_down() {}
	
	final public function get_failure() {
		return $this->failure;
	}
	
	final protected function assert($assertion) {
		if ($assertion !== true && !$this->failure) {
			$backtrace = debug_backtrace();
			$trace = $backtrace[1];
			$this->failure = $trace['function'].', line: '.$trace['line'];
		}
	}
	
	final protected function assert_test($assertion) {
		$this->assert($assertion);
	}
}




/*
final protected function assert_array_has_key($key, $array) {}
final protected function assert_array_has_key_not($key, $array) {}
final protected function assert_class_has_attribute($attribute, $class) {}
final protected function assert_class_has_attribute_not($attribute, $class) {}
final protected function assert_class_has_static_attribute($attribute, $class) {}
final protected function assert_class_has_static_attribute_not($attribute, $class) {}
final protected function assert_contains($needle, $haystack) {}
final protected function assert_contains_not($needle, $haystack) {}
final protected function assert_attribute_contains($needle, $haystack) {}
final protected function assert_attribute_contains_not($needle, $haystack) {}

final protected function assert_contains_only() {}

final protected function assert_equal_xml_structure() {}

final protected function assert_equals() {}

final protected function assert_false() {}

final protected function assert_file_equals() {}

final protected function assert_file_exists() {}

final protected function assert_greater_than() {}

final protected function assert_greater_than_or_equal() {}

final protected function assert_less_than() {}

final protected function assert_less_than_or_equal() {}

final protected function assert_not_null() {}

final protected function assert_object_has_attribute() {}

final protected function assert_reg_exp() {}

final protected function assert_same() {}

final protected function assert_select_count() {}

final protected function assert_select_equals() {}

final protected function assert_select_reg_exp() {}

final protected function assert_string_ends_with() {}

final protected function assert_string_equals_file() {}

final protected function assert_string_starts_with() {}

final protected function assert_tag() {}

final protected function assert_that() {}

final protected function assert_true() {}

final protected function assert_type() {}

final protected function assert_xml_file_equals_xml_file() {}

final protected function assert_xml_string_equals_xml_file() {}

final protected function assert_xml_string_equals_xml_string() {}
*/
?>