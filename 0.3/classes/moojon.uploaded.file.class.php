<?php
final class moojon_uploaded_file extends moojon_base {
	private $name;
	private $type;
	private $tmp_name;
	private $error;
	private $size;
	
	public function __construct($name, $type, $tmp_name, $error, $size) {
		$this->name = $name;
		$this->type = $type;
		$this->tmp_name = $tmp_name;
		$this->error = $error;
		$this->size = $size;
	}
	
	public function get_name() {
		return $this->name;
	}
	
	public function get_type() {
		return $this->type;
	}
	
	public function get_tmp_name() {
		return $this->tmp_name;
	}
	
	public function get_error() {
		return $this->error;
	}
	
	public function get_size() {
		return $this->size;
	}
	
	public function as_array() {
		return array(
			'name' => $this->name,
			'type' => $this->type,
			'tmp_name' => $this->tmp_name,
			'error' => $this->error,
			'size' => $this->size
		);
	}
	
	public function __toString() {
		return $this->name;
	}
}
?>