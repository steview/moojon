<?php
class moojon_query extends moojon_query_utilities
{
	final private function __construct() {}
	
	final static public function build($obj, $command = '', $data = array(), $where = '', $order = '', $limit = '', $joins = array()) {
		$builder = self::find_builder(func_get_args());
		$joins = self::resolve($joins, $builder, 'joins');
		$join_string = ' ';
		if (empty($data)) {
			if (strtoupper($command) != 'DESC' && strtoupper($command) != 'DESC') {
				$data[] = '*';
			}			
		}
		if (get_class($builder) == 'moojon_query_builder') {
			$data = $builder->map_data();			
		}
		if (!empty($joins) && is_array($joins))
		{
			foreach($builder->joins as $join)
			{
				$join_string .= $join->render($builder).' ';
			}
		}
		$join_string = rtrim($join_string);
		$obj = self::resolve($obj, $builder, 'obj');
		$command = strtoupper(self::resolve($command, $builder, 'command'));
		$data = self::resolve($data, $builder, 'data');
		$where = self::resolve($where, $builder, 'where', ' WHERE %s');
		$order = self::resolve($order, $builder, 'order', ' ORDER BY %s ');
		$limit = self::resolve($limit, $builder, 'limit', ' LIMIT %s');
		$query = "$command ";
		switch ($command)
		{
			case 'SELECT':
				if (is_array($data))
				{
					foreach(array_keys($data) as $key)
					{
						if (!is_string($key))
						{
							$data_string .= ', '.$data[$key];
						}
						else
						{
							$data_string .= ", $key AS ".$data[$key];
						}
					}
					$data_string = substr($data_string, 2);
					$data = $data_string;
				}
				$query .= "$data FROM $obj$join_string$where$order$limit;";
				break;
			case 'INSERT':
				foreach($data as $value)
				{
					$values .= ", '".sprintf('%s', $value)."'";
				}
				$values = ' VALUES('.substr($values, 2).')';
				$columns = implode(', ', array_keys($data));
				$query .= "INTO $obj ($columns)$values;";
				break;
			case 'UPDATE':
				foreach($data as $key => $value)
				{
					$values .= ", $key = '".sprintf('%s', $value)."'";
				}
				$query .= "$obj SET ".substr($values, 2)."$where;";
				break;
			case 'DELETE':
				$query .= " FROM $obj$where$limit;";
				break;
			case 'DESCRIBE':
			case 'DESC':
				if (is_array($data)) {
					$data_string = '';
					foreach(array_keys($data) as $key) {
						$data_string .= ', '.$data[$key];
					}
					$data_string = substr($data_string, 2);
					$data = $data_string;
				}
				$query .= $obj;
				if (strlen($data) > 0) {
					$query .= " $data";
				}
				$query .= ';';
				break;
			case 'SHOW COLUMNS':
			case 'SHOW FULL COLUMNS':
				$query .= "FROM $obj";
				if (strlen($where) > 0) {
					$query .= " $where";
				} 
				$query .= ';';
				break;
			case 'SHOW TABLES':
			case 'SHOW FULL TABLES':
				if (strlen($obj) > 0) {
					$query .= "FROM $obj";
				}
				if (strlen($where) > 0) {
					$query .= " $where";
				} 
				$query .= ';';
				break;
		}
		return $query;
	}
	
	final static public function run($obj, $command = '', $data = array(), $where = '', $order = '', $limit = '', $joins = array(), $test = false) {
		$query = self::build($obj, $command = '', $data = array(), $where = '', $order = '', $limit = '', $joins = array());
		$args = func_get_args();
		return self::run_raw($query, self::resolve($test, self::find_builder($args), 'test'));
	}
	
	final static public function run_raw($query, $test = false) {
		if ($test === true)
		{
			die($query);
		}
		//echo "Running: $query<br />";
		$query = mysql_query($query, moojon_connection::init()->get_resource());
		//echo "Errors: ".mysql_error();
		$result = array();
		if (gettype($query) == 'resource')
		{
			while ($row = mysql_fetch_assoc($query))
			{
			    $result[] = $row;
			}
		}	
		else
		{
			$result[] = $query;
		}
		return $result;
	}
}

class query extends moojon_query {}
class q extends moojon_query {}
?>