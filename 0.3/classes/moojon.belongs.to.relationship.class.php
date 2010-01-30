<?php
final class moojon_belongs_to_relationship extends moojon_base_relationship {
	private $shared_columns = array();
	
	public function has_shared_column($key) {
		return in_array($key, $this->shared_columns);
	}
	
	public function set_shared_columns($shared_columns = array()) {
		$this->shared_columns = $shared_columns;
	}
	
	public function get_shared_columns($exceptions = array()) {
		$return = array();
		foreach ($this->shared_columns as $column) {
			if (!in_array($column, $exceptions)) {
				$return[] = $column;
			}
		}
		return $return;
	}
}
?>