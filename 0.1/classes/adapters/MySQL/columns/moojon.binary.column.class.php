<?php
class moojon_binary_column extends moojon_base_column {
	protected function validate($value) {return true;}
}

class binary_column extends moojon_binary_column {}
?>