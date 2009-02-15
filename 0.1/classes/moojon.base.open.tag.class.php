<?php
abstract class moojon_base_open_tag extends moojon_base_tag {
	
	final public function __construct($children = null, $attributes = null) {
		$this->init();
		parent::__construct($children, $attributes);
	}
	
	abstract protected function init();
	
	final public function render() {
		$render = '<'.$this->name;
		foreach ($this->attributes as $attribute) {
			$render .= ' '.$attribute->render();
		}
		$render .= '>';
		foreach ($this->children as $child) {
			if (get_class($child) == 'moojon_base_tag') {
				$render .= $child->render();
			} else {
				$render .= $child;
			}
		}
		return $render.'</'.$this->name.'>';
	}
	
	final protected function get_property($key) {
		$child = $this->get_child($key);
		if ($child != null) {
			return $child;
		} else {
			self::handle_error("No such attribute or child ($key)");
		}
	}
}
?>