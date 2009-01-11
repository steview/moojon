<?php
class moojon_time_column extends moojon_base_column {
	protected function validate($value) {return true;}
}

class time_column extends moojon_time_column {}
?>