<?php
class moojon_bit_column extends moojon_base_column {
	protected function validate($value) {return true;}
}

class bit_column extends moojon_bit_column {}
?>