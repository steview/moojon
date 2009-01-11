<?php
class moojon_query extends moojon_query_utilities
{
	final private function __construct() {}
	
	final static public function run($obj, $command = '', $data = array(), $where = '', $order = '', $limit = '', $joins = array(), $test = false)
	{
		$builder = self::find_builder(func_get_args());
		$joins = self::resolve($joins, $builder, 'joins');
		$join_string = ' ';
		if (empty($data))
		{
			$data[] = '*';
		}
		if (get_class($builder) == 'moojon_query_builder')
		{
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
		$test = self::resolve($test, $builder, 'test');
		$sql = "$command ";
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
				$sql .= "$data FROM $obj$join_string$where$order$limit;";
				break;
			case "INSERT":
				foreach($data as $value)
				{
					$values .= ", '".sprintf('%s', $value)."'";
				}
				$values = ' VALUES('.substr($values, 2).')';
				$columns = implode(', ', array_keys($data));
				$sql .= "INTO $obj ($columns)$values;";
				break;
			case "UPDATE":
				foreach($data as $key => $value)
				{
					$values .= ", $key = '".sprintf('%s', $value)."'";
				}
				$sql .= "$obj SET ".substr($values, 2)."$where;";
				break;
			case "DELETE":
				$sql .= " FROM $obj$where$limit;";
				break;
		}
		//die($sql);
		if ($test === true)
		{
			die($sql);
		}		
		return self::run_raw($sql);
	}
	
	final static public function run_raw($sql)
	{
		echo "Running: $sql<br />";
		$query = mysql_query($sql, moojon_connection::init()->get_resource());
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