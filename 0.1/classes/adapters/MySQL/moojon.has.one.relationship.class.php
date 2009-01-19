<?php
final class moojon_has_one_relationship extends moojon_base_relationship {
	public function get_where(moojon_base_model $accessor) {
		$foreign_obj = $this->foreign_obj;
		$foreign_key = moojon_model_properties::get_foreign_key($foreign_obj);
		$key = $this->key;
		return "$foreign_obj.$key = ".$accessor->$foreign_key;
	}
}
?>