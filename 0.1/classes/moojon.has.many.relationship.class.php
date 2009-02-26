<?php
final class moojon_has_many_relationship extends moojon_base_relationship {
	public function get_where(moojon_base_model $accessor) {
		$foreign_obj = $this->foreign_obj;
		$foreign_key = moojon_primary_key::get_foreign_key(get_class($accessor));
		$key = $this->key;
		return "$foreign_obj.$foreign_key = ".$accessor->$key;
	}
}
?>