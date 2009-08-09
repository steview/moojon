<?php
abstract class moojon_base_tag extends moojon_base {
	
	public $node_name;
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
			$attribute->set_value($value);
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
			throw new moojon_exception("Duplicate attribute ($name)");
		}
		if (in_array($name, $this->legal_attributes) == true) {
			$this->attributes[$name] = $attribute;
		} else {
			throw new moojon_exception("Illegal attribute ($name)");
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
			return $this->attributes[$key];
		} else {
			throw new moojon_exception("No such attribute ($key)");
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
			throw new moojon_exception("No such attribute or child ($key)");
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
	
	final public function prepend_child($child) {
		array_unshift($this->children, $child);
	}
	
	final public function add_class($class) {
		if (in_array('class', $this->legal_attributes)) {
			if ($this->has_attribute('class')) {
				$this->class = $this->class.' '.$class;
			} else {
				$this->class = $class;
			}
		} else {
			throw new moojon_exception('Tag can not have class attribute ('.get_class($this).')');
		}
	}
	
	final public function get_by_attribute($attribute, $value = null, $recursive = false) {
		$return = array();
		foreach ($this->children as $child) {
			if (is_subclass_of($child, 'moojon_base_tag') == true) {
				if ($child->has_attribute($attribute) == true) {
					if ($value != null) {
						if ($child->$attribute->get_value() == $value) {
							$return[] = $child;
						}
					} else {
						$return[] = $child;
					}
				}
				if (is_subclass_of($child, 'moojon_base_open_tag') == true && $recursive == true) {
					$return = array_merge($return, $child->get_by_attribute($attribute, $value));
				}
			}
		}
		return $return;
	}
	
	final public function remove_by_attribute($attribute, $value = null, $recursive = false) {
		$remaining_children = array();
		foreach ($this->children as $child) {
			if (is_object($child) && is_subclass_of($child, 'moojon_base_tag') == true) {
				if ($child->has_attribute($attribute) == true) {
					if ($value != null) {
						if ($child->$attribute->get_value() == $value) {
							$child = null;
						}
					} else {
						$child = null;
					}
				}
				if ($child != null && is_subclass_of($child, 'moojon_base_open_tag') == true && $recursive == true) {
					$child->remove_by_attribute($attribute, $value, $recursive);
				}
			}
			if ($child != null) {
				$remaining_children[] = $child;
			}
		}
		$this->children = $remaining_children;
	}
}
?>