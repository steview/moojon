<?php
final class moojon_date_validation extends moojon_base_validation {

	public function __construct($message) {
		$this->set_message($message);
	}

	public function validate(moojon_base_column $column) {
		$stamp = strtotime($column->get_value());
		$month = date('m', $stamp);
		$day   = date('d', $stamp);
		$year  = date('Y', $stamp);
		return checkdate($day, $month, $year);
	}
}
?>