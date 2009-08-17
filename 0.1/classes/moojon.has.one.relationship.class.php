<?php
final class moojon_has_one_relationship extends moojon_base_relationship {
	public function get_where(moojon_base_model $accessor) {
		$foreign_table = $this->foreign_table;
		$foreign_key = moojon_primary_key::get_foreign_key($foreign_table);
		$key = $this->key;
		return "$foreign_table.$key = ".$accessor->$foreign_key;
	}
}
?>