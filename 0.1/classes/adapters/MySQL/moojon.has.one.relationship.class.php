<?php
final class moojon_has_one_relationship extends moojon_base_relationship
{
	public function get_where(base_moojon_model $accessor) {
		$foreign_obj = $this->foreign_obj;
		$foreign_key = $this->foreign_key;
		$key = $this->key;
		return "$foreign_obj.$foreign_key = ".$accessor->$key;
	}
}
?>