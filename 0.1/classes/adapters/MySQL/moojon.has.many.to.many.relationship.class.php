<?php
final class moojon_has_many_to_many_relationship extends moojon_base_relationship
{
	final public function get_where(base_moojon_model $accessor) {
		
	}
	
	final public function get_class(base_moojon_model $accessor) {
		$classes = array();
		$classes[] = moojon_inflect::singularize($this->foreign_obj);
		$classes[] = get_class($accessor);
		sort($classes);
		return $classes[0].'_'.$classes[1];
	}
	
	final private function get_obj() {
		return moojon_inflect::pluralize($this->get_class());
	}
}
?>