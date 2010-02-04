<?php
final class moojon_creditcard_validation extends moojon_base_validation {
	
	public function get_data_keys() {
		return array('data', 'card_type');
	}
	
	public function valid($data) {
		$card_number = $data['data'];
		$cards = array(
			array('type' => 'american_express', 'length' => '15', 'prefixes' => '34,37', 'checkdigit' => true),
			array('type' => 'diners_club_carte_blanche', 'length' => '14', 'prefixes' => '300,301,302,303,304,305', 'checkdigit' => true),
			array('type' => 'diners_club', 'length' => '14,16', 'prefixes' => '36,54,55', 'checkdigit' => true),
			array('type' => 'discover', 'length' => '16', 'prefixes' => '6011,622,64,65', 'checkdigit' => true),
			array('type' => 'diners_club_enroute', 'length' => '15', 'prefixes' => '2014,2149', 'checkdigit' => true),
			array('type' => 'jcb', 'length' => '16', 'prefixes' => '35', 'checkdigit' => true),
			array('type' => 'maestro', 'length' => '12,13,14,15,16,18,19', 'prefixes' => '5018,5020,5038,6304,6759,6761', 'checkdigit' => true),
			array('type' => 'mastercard', 'length' => '16', 'prefixes' => '51,52,53,54,55', 'checkdigit' => true),
			array('type' => 'solo', 'length' => '16,18,19', 'prefixes' => '6334,6767', 'checkdigit' => true),
			array('type' => 'switch', 'length' => '16,18,19', 'prefixes' => '4903,4905,4911,4936,564182,633110,6333,6759', 'checkdigit' => true),
			array('type' => 'visa', 'length' => '13,16', 'prefixes' => '4', 'checkdigit' => true),
			array('type' => 'visa_electron', 'length' => '16', 'prefixes' => '417500,4917,4913,4508,4844', 'checkdigit' => true)
		);
		$card_type = -1;
		for ($i = 0; $i < count($cards); $i ++) {
			if (strtolower(str_replace(' ', '_', $data['card_type'])) == $cards[$i]['type']) {
				$card_type = $i;
				break;
			}
		}
		if ($card_type == -1) {
			return false;
		}
		if (!strlen($card_number)) {
			return false;
		}
		$card_number = str_replace (' ', '', $card_number);
		if (!eregi('^[0-9]{13,19}$', $card_number)) {
			return false;
		}
		if ($cards[$card_type]['checkdigit']) {
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
			if ($checksum % 10) {
				return false;
			}
		}
		$prefix = split(',', $cards[$card_type]['prefixes']);
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
		$lengths = split(',', $cards[$card_type]['length']);
		for ($i = 0; $i < sizeof($lengths); $i ++) {
			if (strlen($card_number) == $lengths[$i]) {
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