<?php
final class moojon_creditcard_validation extends moojon_base_validation {
	
	private $card_type;
	
	public function __construct($message, $card_type) {
		$this->set_message($message);
		$this->card_type = $card_type;
	}
	
	public function validate(moojon_base_column $column) {
		$card_number = $column->get_value();
		$cards = array(
			array('type' => 'American Express', 'length' => '15', 'prefixes' => '34,37', 'checkdigit' => true),
			array('type' => 'Diners Club Carte Blanche', 'length' => '14', 'prefixes' => '300,301,302,303,304,305', 'checkdigit' => true),
			array('type' => 'Diners Club', 'length' => '14,16', 'prefixes' => '36,54,55', 'checkdigit' => true),
			array('type' => 'Discover', 'length' => '16', 'prefixes' => '6011,622,64,65', 'checkdigit' => true),
			array('type' => 'Diners Club Enroute', 'length' => '15', 'prefixes' => '2014,2149', 'checkdigit' => true),
			array('type' => 'JCB', 'length' => '16', 'prefixes' => '35', 'checkdigit' => true),
			array('type' => 'Maestro', 'length' => '12,13,14,15,16,18,19', 'prefixes' => '5018,5020,5038,6304,6759,6761', 'checkdigit' => true),
			array('type' => 'MasterCard', 'length' => '16', 'prefixes' => '51,52,53,54,55', 'checkdigit' => true),
			array('type' => 'Solo', 'length' => '16,18,19', 'prefixes' => '6334,6767', 'checkdigit' => true),
			array('type' => 'Switch', 'length' => '16,18,19', 'prefixes' => '4903,4905,4911,4936,564182,633110,6333,6759', 'checkdigit' => true),
			array('type' => 'Visa', 'length' => '13,16', 'prefixes' => '4', 'checkdigit' => true),
			array('type' => 'Visa Electron', 'length' => '16', 'prefixes' => '417500,4917,4913,4508,4844', 'checkdigit' => true)
		);
		$this->card_type = -1;
		for ($i = 0; $i < sizeof($cards); $i ++) {
			if (strtolower($this->card_type) == strtolower($cards[$i]['name'])) {
				$this->card_type = $i;
				break;
			}
		}
		if ($this->card_type == -1) {
			return false;
		}
		if (strlen($card_number) == 0) {
			return false;
		}
		$card_number = str_replace (' ', '', $card_number);
		if (!eregi('^[0-9]{13,19}$', $card_number)) {
			return false;
		}
		if ($cards[$this->card_type]['checkdigit']) {
			$checksum = 0;
			$mychar = '';
			$j = 1;
			for ($i = strlen($card_number) - 1; $i >= 0; $i --) {
				$calc = $card_number{$i} * $j;
				if ($calc > 9) {
					$checksum = $checksum + 1;
					$calc = ($calc - 10);
				}
				$checksum = $checksum + $calc;
				if ($j == 1) {
					$j = 2;
				} else {
					$j = 1;
				}
			}
			if ($checksum % 10 != 0) {
				return false;
			}
		}
		$prefix = split(',', $cards[$this->card_type]['prefixes']);
		$prefix_valid = false;
		for ($i = 0; $i < sizeof($prefix); $i ++) {
			$exp = '^'.$prefix[$i];
			if (ereg($exp, $card_number)) {
				$prefix_valid = true;
				break;
			}
		}
		if (!$prefix_valid) {
			return false;
		}
		$length_valid = false;
		$lengths = split(',', $cards[$this->card_type]['length']);
		for ($j = 0; $j < sizeof($lengths); $j ++) {
			if (strlen($card_number) == $lengths[$j]) {
				$length_valid = true;
				break;
			}
		}
		if (!$length_valid) {
			return false;
		}
		return true;
	}
}
?>