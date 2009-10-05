<?php
class test_test_suite extends base_test_suite {
	public function test_test() {
		$this->assert_test((1 == 2));
	}
}
?>