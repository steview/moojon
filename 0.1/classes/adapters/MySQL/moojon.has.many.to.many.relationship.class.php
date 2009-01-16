<?php
final class moojon_has_many_to_many_relationship extends moojon_base_relationship
{
	final public function get_where(moojon_base_model $accessor) {
		$obj = $this->get_obj($accessor);
		$key = $this->key;
		$foreign_key = $this->foreign_key;
		return "$key IN (SELECT ".moojon_model_properties::get_foreign_key($this->foreign_obj)." FROM $obj WHERE $foreign_key = ".$accessor->$key.')';
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