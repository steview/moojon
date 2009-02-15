<?php
abstract class moojon_base_tag extends moojon_base {
	
	protected $node_name;
	protected $legal_attributes = array();
	protected $attributes = array();
	protected $children = array();
	
	public function __construct($children = null, $attributes = null) {
		if (is_array($children) == false) {
			$children = array($children);
		}
		foreach($children as $child) {
			$this->add_child($child);
		}
		if ($attributes == null) {
			$attributes = array();
		}
		if (is_array($attributes) == false) {
			$attributes = array($attributes => $attributes);
		}
		foreach ($attributes as $key => $value) {
			$this->add_attribute($key, $value);
		}
	}
	
	abstract public function render();
	
	final public function __get($key) {
		if ($this->has_attribute($key) == true) {
			return $this->get_attribute($key);
		} else {
			return $this->get_property($key);
		}
	}
	
	abstract protected function get_property($key);
	
	final public function __set($key, $value) {
		$this->set_attribute($key, $value);
	}
	
	final protected function set_attribute($key, $value) {
		if ($this->has_attribute($key) == true) {
			$attribute = $this->get_attribute($key);
			//$attribute->set_value($value);
		} else {
			$this->add_attribute($key, $value);
		}
	}
	
	final public function add_attribute($key, $value) {
		if (get_class($key) != 'moojon_base_tag_attribute') {
			$key = 'moojon_'.$key.'_tag_attribute';
			$attribute = new $key($value);
		}
		$name = $attribute->get_name();
		if ($this->has_attribute($name)) {
			self::handle_error("Duplicate attribute ($name)");
		}
		if (in_array($name, $this->legal_attributes) == true) {
			$this->attributes[$name] = $attribute;
		} else {
			self::handle_error("Illegal attribute ($name)");
		}
	}
	
	final public function has_attribute($key) {
		return array_key_exists($key, $this->attributes);
	}
	
	final public function has_attributes() {
		if (count($this->attributes) > 0) {
			return true;
		} else {
			return flase;
		}
	}
	
	final public function get_attribute($key) {
		if ($this->has_attribute($key)) {
			return $this->attribute[$key];
		} else {
			self::handle_error("No such attribute ($key)");
		}
	}
	
	final public function get_attributes() {
		return $this->attributes;
	}
	
	final public function has_child($key) {
		foreach ($this->get_children() as $child) {
			if (is_subclass_of($child, 'moojon_base_tag') && $child->has_attribute('id') && $child->id == $key) {
				return true;
			}
		}
		return false;
	}
	
	final public function get_child($key) {
		if ($this->has_child($key)) {
			foreach ($this->get_children() as $child) {
				if (is_subclass_of($child, 'moojon_base_tag') && $child->has_attribute('id') && $child->id == $key) {
					return $child;
				}
			}
		} else {
			self::handle_error("No such attribute or child ($key)");
		}
	}
	
	final public function get_children() {
		return $this->children;
	}
	
	final public function has_children() {
		if (count($this->children) > 0) {
			return true;
		} else {
			return flase;
		}
	}
	
	final public function add_child($child) {
		$this->children[] = $child;
	}
}
?>