<?php
final class moojon_date_validation extends moojon_base_validation {
	
	static public function valid($data) {
		$stamp = self::get_time($data['data']);
		$month = date('m', $stamp);
		$day   = date('d', $stamp);
		$year  = date('Y', $stamp);
		return checkdate($day, $month, $year);
	}
}
?>