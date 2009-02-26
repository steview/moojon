<?php
final class moojon_has_many_to_many_relationship extends moojon_base_relationship
{
	final public function get_where(moojon_base_model $accessor) {
		$foreign_obj = $this->get_obj($accessor);
		$key = $this->key;
		$foreign_key1 = moojon_primary_key::get_foreign_key($this->foreign_obj);
		$foreign_key2 = moojon_primary_key::get_foreign_key(get_class($accessor));
		return "$key IN (SELECT $foreign_key1 FROM $foreign_obj WHERE $foreign_key2 = ".$accessor->$key.')';
	}
	
	final public function get_class(moojon_base_model $accessor) {
		$classes = array();
		$classes[] = moojon_inflect::singularize($this->foreign_obj);
		$classes[] = get_class($accessor);
		sort($classes);
		return $classes[0].'_'.$classes[1];
	}
	
	final private function get_obj(moojon_base_model $accessor) {
		return moojon_inflect::pluralize($this->get_class($accessor));
	}
}
?>