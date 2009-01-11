<?php
class moojon_query_builder extends moojon_query_utilities
{
	private $primary_obj = null;
	public $obj;
	public $command;
	public $data = array();
	public $where;
	public $order;
	public $limit;
	public $test = false;
	public $joins;
	public $foreign_table;
	public $foreign_key;
	
	final private function __construct($command, $obj, $data, $where, $order, $limit)
	{
		return $this->command_obj($command, $obj, $data, $where, $order, $limit);
	}
	
	final static public function init($command = null, $obj = null, $data = null, $where = null, $order = null, $limit = null)
	{
		return new moojon_query_builder($command, $obj, $data, $where, $order, $limit);
	}
	
	final public function select($obj = null, $data = '*', $where = null, $order = null, $limit = null)
	{
		return $this->command_obj('SELECT', $obj, $data, $where, $order, $limit);
	}
	
	final public function insert($obj = null, $data = null)
	{
		return $this->command_obj('INSERT', $obj, $data, null, null, null);
	}
	
	final public function update($obj = null, $data = null, $where = null)
	{
		return $this->command_obj('UPDATE', $obj, $data, $where, null, null);
	}
	
	final public function delete($obj = null, $where = null)
	{
		return $this->command_obj('DELETE', $obj, null, $where, null, null);
	}
	
	final private function command_obj($command, $obj, $data, $where, $order, $limit)
	{
		$this->command = $command;
		if ($obj)
		{
			$this->obj($obj);
		}
		if ($data)
		{
			$this->data($data);
		}
		if ($where)
		{
			$this->where($where);
		}
		if ($order)
		{
			$this->order($order);
		}
		if ($limit)
		{
			$this->limit($limit);
		}
		return $this;
	}
	
	final public function obj($obj)
	{
		if (!$this->obj)
		{
			$this->primary_obj = $obj;
		}
		$this->obj = $obj;
		return $this;
	}
	
	final public function add_obj($obj)
	{
		if (!empty($this->obj))
		{
			$obj = $this->obj.", $obj"; 
		}
		return $this->obj($obj);
	}
	
	final public function command($command)
	{
		$this->command = $command;
		return $this;
	}
	
	final public function data($data)
	{
		$this->data = $data;
		return $this;
	}
	
	final public function add_data($data)
	{
		if (is_array($this->data))
		{
			$this->data[] = $data;
		}
		else
		{
			$this->data = $this->data.', '.$data; 
		}
		return $this;
	}
	
	final public function map_data()
	{
		if (is_array($this->data))
		{
			$data = array();
			$obj = $this->primary_obj;
			foreach ($this->data as $key => $val)
			{
				$assoc = true;
				if (!is_string($key))
				{
					$key = $val;
					$assoc = false;
				}
				$map_key = $key;
				if (substr($key, 0, (strlen($obj) + 1)) == "$obj.")
				{
					$map_key = "$obj.".substr($key, (strlen($obj) + 1));
				}
				if ($assoc)
				{
					$data[$map_key] = $val;
				}
				else
				{
					$data[] = "$obj.$key";
				}
			}
			return $data;
		}
		else
		{
			return $this->data;
		}
	}
	
	final public function where($where, $value = null)
	{
		if ($value == null)
		{
			$this->where = $where;
		}
		else
		{
			$this->where = sprintf($where, trim($value));
		}
		return $this;
	}
	
	final public function add_where($where, $value = null, $opperator = 'AND')
	{
		if ($value != null)
		{
			$where = sprintf($where, trim($value));
		}
		$opperator = ' '.trim($opperator).' ';
		if (!empty($this->where))
		{
			$existing = $this->where;
		}
		else
		{
			$opperator = '';
			$existing = '';
		}
		return $this->where($existing.$opperator.$where);
	}
	
	final public function and_where($where, $value = null)
	{
		return $this->add_where($where, $value, 'AND');
	}
	
	final public function or_where($where, $value = null)
	{
		return $this->add_where($where, $value, 'OR');
	}
	
	final public function order($order)
	{
		$this->order = $order;
		return $this;
	}
	
	final public function limit($limit)
	{
		$this->limit = $limit;
		return $this;
	}
	
	final public function joins($joins)
	{
		$this->joins = $joins;
		return $this;
	}
	
	final public function join($type, $foreign_table, $local_table = null, $foreign_key = null, $local_key = null)
	{
		if ($local_table == null)
		{
			$local_table = $this->obj;
		}
		
		if ($foreign_key == null)
		{
			$foreign_key = moojon_model_properties::get_foreign_key($foreign_key);
		}
		
		if ($local_key == null)
		{
			$local_key = moojon_model_properties::DEFAULT_PRIMARY_KEY;
		}
		$this->joins[] = moojon_joins::init($type, $foreign_table, $local_table, $foreign_key, $local_key);
		return $this;
	}
	
	final public function left($foreign_table, $local_table = null, $foreign_key = null, $local_key = null)
	{
		return $this->join('left', $foreign_table, $local_table, $foreign_key, $local_key);
	}
	
	final public function right($foreign_table, $local_table = null, $foreign_key = null, $local_key = null)
	{
		return $this->join('right', $foreign_table, $local_table, $foreign_key, $local_key);
	}
	
	final public function inner($foreign_table, $local_table = null, $foreign_key = null, $local_key = null)
	{
		return $this->join('inner', $foreign_table, $local_table, $foreign_key, $local_key);
	}
	
	final public function equi($foreign_table, $local_table = null, $foreign_key = null, $local_key = null)
	{
		return $this->inner($foreign_table, $local_table, $foreign_key, $local_key);
	}
	
	final public function cross($foreign_table)
	{
		return $this->join('cross', $foreign_table);
	}
	
	final public function run($test = null)
	{
		if (!empty($test))
		{
			$this->test($test);
		}
		return moojon_query::run($this);
	}
	
	final public function test($test = true)
	{
		$this->test = $test;
		return $this;
	}
}

class mqb extends moojon_query_builder {}
class qb extends moojon_query_builder {}
?>