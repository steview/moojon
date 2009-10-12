<?php
final class moojon_date_validation extends moojon_base_validation {

	public function __construct($message, $required = true) {
		$this->set_message($message);
		$this->required = $required;
	}

	public function valid(moojon_base_model $model, moojon_base_column $column) {
		$stamp = self::get_time($column->get_value());
		$month = date('m', $stamp);
		$day   = date('d', $stamp);
		$year  = date('Y', $stamp);
		return checkdate($day, $month, $year);
	}
}
?>