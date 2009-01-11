<?php
class moojon_varbinary_column extends moojon_base_column {
	protected function validate($value) {return true;}
}

class varbinary_column extends moojon_varbinary_column {}
?>