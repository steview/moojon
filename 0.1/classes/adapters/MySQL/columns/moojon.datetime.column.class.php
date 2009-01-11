<?php
class moojon_datetime_column extends moojon_base_column {
	protected function validate($value) {return true;}
}

class datetime_column extends moojon_datetime_column {}
?>