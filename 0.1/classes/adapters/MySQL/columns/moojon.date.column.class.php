<?php
class moojon_date_column extends moojon_base_column {
	protected function validate($value) {return true;}
}

class date_column extends moojon_date_column {}
?>