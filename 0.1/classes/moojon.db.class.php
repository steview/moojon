<?php
final class moojon_db  extends moojon_base {
	const PARAM_BOOL = PDO::PARAM_BOOL;
	const PARAM_NULL = PDO::PARAM_NULL;
	const PARAM_INT = PDO::PARAM_INT;
	const PARAM_STR = PDO::PARAM_STR;
	const PARAM_LOB = PDO::PARAM_LOB;
	const PARAM_STMT = PDO::PARAM_STMT;
	const PARAM_INPUT_OUTPUT = PDO::PARAM_INPUT_OUTPUT;
	const FETCH_LAZY = PDO::FETCH_LAZY;
	const FETCH_ASSOC = PDO::FETCH_ASSOC;
	const FETCH_NAMED = PDO::FETCH_NAMED;
	const FETCH_NUM = PDO::FETCH_NUM;
	const FETCH_BOTH = PDO::FETCH_BOTH;
	const FETCH_OBJ = PDO::FETCH_OBJ;
	const FETCH_BOUND = PDO::FETCH_BOUND;
	const FETCH_COLUMN = PDO::FETCH_COLUMN;
	const FETCH_CLASS = PDO::FETCH_CLASS;
	const FETCH_INTO = PDO::FETCH_INTO;
	const FETCH_FUNC = PDO::FETCH_FUNC;
	const FETCH_GROUP = PDO::FETCH_GROUP;
	const FETCH_UNIQUE = PDO::FETCH_UNIQUE;
	const FETCH_KEY_PAIR = PDO::FETCH_KEY_PAIR;
	const FETCH_CLASSTYPE = PDO::FETCH_CLASSTYPE;
	const FETCH_SERIALIZE = PDO::FETCH_SERIALIZE;
	const FETCH_PROPS_LATE = PDO::FETCH_PROPS_LATE;
	const ATTR_AUTOCOMMIT = PDO::ATTR_AUTOCOMMIT;
	const ATTR_PREFETCH = PDO::ATTR_PREFETCH;
	const ATTR_TIMEOUT = PDO::ATTR_TIMEOUT;
	const ATTR_ERRMODE = PDO::ATTR_ERRMODE;
	const ATTR_SERVER_VERSION = PDO::ATTR_SERVER_VERSION;
	const ATTR_CLIENT_VERSION = PDO::ATTR_CLIENT_VERSION;
	const ATTR_SERVER_INFO = PDO::ATTR_SERVER_INFO;
	const ATTR_CONNECTION_STATUS = PDO::ATTR_CONNECTION_STATUS;
	const ATTR_CASE = PDO::ATTR_CASE;
	const ATTR_CURSOR_NAME = PDO::ATTR_CURSOR_NAME;
	const ATTR_CURSOR = PDO::ATTR_CURSOR;
	const ATTR_DRIVER_NAME = PDO::ATTR_DRIVER_NAME;
	const ATTR_ORACLE_NULLS = PDO::ATTR_ORACLE_NULLS;
	const ATTR_PERSISTENT = PDO::ATTR_PERSISTENT;
	const ATTR_STATEMENT_CLASS = PDO::ATTR_STATEMENT_CLASS;
	const ATTR_FETCH_CATALOG_NAMES = PDO::ATTR_FETCH_CATALOG_NAMES;
	const ATTR_FETCH_TABLE_NAMES = PDO::ATTR_FETCH_TABLE_NAMES;
	const ATTR_STRINGIFY_FETCHES = PDO::ATTR_STRINGIFY_FETCHES;
	const ATTR_MAX_COLUMN_LEN = PDO::ATTR_MAX_COLUMN_LEN;
	const ATTR_DEFAULT_FETCH_MODE = PDO::ATTR_DEFAULT_FETCH_MODE;
	const ATTR_EMULATE_PREPARES = PDO::ATTR_EMULATE_PREPARES;
	const ERRMODE_SILENT = PDO::ERRMODE_SILENT;
	const ERRMODE_WARNING = PDO::ERRMODE_WARNING;
	const ERRMODE_EXCEPTION = PDO::ERRMODE_EXCEPTION;
	const CASE_NATURAL = PDO::CASE_NATURAL;
	const CASE_LOWER = PDO::CASE_LOWER;
	const CASE_UPPER = PDO::CASE_UPPER;
	const NULL_NATURAL = PDO::NULL_NATURAL;
	const NULL_EMPTY_STRING = PDO::NULL_EMPTY_STRING;
	const NULL_TO_STRING = PDO::NULL_TO_STRING;
	const FETCH_ORI_NEXT = PDO::FETCH_ORI_NEXT;
	const FETCH_ORI_PRIOR = PDO::FETCH_ORI_PRIOR;
	const FETCH_ORI_FIRST = PDO::FETCH_ORI_FIRST;
	const FETCH_ORI_LAST = PDO::FETCH_ORI_LAST;
	const FETCH_ORI_ABS = PDO::FETCH_ORI_ABS;
	const FETCH_ORI_REL = PDO::FETCH_ORI_REL;
	const CURSOR_FWDONLY = PDO::CURSOR_FWDONLY;
	const CURSOR_SCROLL = PDO::CURSOR_SCROLL;
	const ERR_NONE = PDO::ERR_NONE;
	const PARAM_EVT_ALLOC = PDO::PARAM_EVT_ALLOC;
	const PARAM_EVT_FREE = PDO::PARAM_EVT_FREE;
	const PARAM_EVT_EXEC_PRE = PDO::PARAM_EVT_EXEC_PRE;
	const PARAM_EVT_EXEC_POST = PDO::PARAM_EVT_EXEC_POST;
	const PARAM_EVT_FETCH_PRE = PDO::PARAM_EVT_FETCH_PRE;
	const PARAM_EVT_FETCH_POST = PDO::PARAM_EVT_FETCH_POST;
	const PARAM_EVT_NORMALIZE = PDO::PARAM_EVT_NORMALIZE;
	static private $instance;
	private $data;
	
	private function __construct() {
		$db_driver = moojon_config::key('db_driver');
		if (substr($db_driver, -1) == ':') {
			$db_driver = substr($db_driver, -1);
		}
		if (moojon_config::has('db_dsn')) {
			$db_dsn = moojon_config::key('db_dsn');
		} else {
			$keys = array();
			switch($db_driver) {
				case 'sybase':
				case 'mssql':
				case 'dblib':
					$keys['host'] = 'db_host';
					$keys['dbname'] = 'db_dbname';
					$keys['charset'] = 'db_charset';
					$keys['appname'] = 'db_appname';
					$keys['secure'] = 'db_secure';
					break;
				case 'firebird':
					$keys['dbname'] = 'db_dbname';
					$keys['charset'] = 'db_charset';
					$keys['role'] = 'db_role';
					break;
				case 'ibm':
					$keys['database'] = 'db_database';
					$keys['hostname'] = 'db_hostname';
					$keys['port'] = 'db_port';
					$keys['username'] = 'db_username';
					$keys['password'] = 'db_password';
					break;
				case 'informix':
				case 'sqlite':
				case 'sqlite2':
					throw new moojon_excpetion("db_driver ($db_driver) requires db_dsn config key");
					break;
				case 'mysql':
					$keys['host'] = 'db_host';
					$keys['port'] = 'db_port';
					$keys['dbname'] = 'db_dbname';
					$keys['unix_socket'] = 'db_unix_socket';
					break;
				case 'oci':
					$keys['dbname'] = 'db_dbname';
					$keys['charset'] = 'db_charset';
					break;
				case 'odbc':
					$keys['dsn'] = 'db_dsn_name';
					$keys['uid'] = 'db_uid';
					$keys['pwd'] = 'db_pwd';
					break;
				case 'pgsql':
					$keys['host'] = 'db_host';
					$keys['port'] = 'db_port';
					$keys['dbname'] = 'db_dbname';
					$keys['user'] = 'db_user';
					$keys['password'] = 'db_password';
					break;
				case '4D':
					$keys['host'] = 'db_host';
					$keys['port'] = 'db_port';
					$keys['dbname'] = 'db_dbname';
					$keys['chars_set'] = 'db_chars_set';
					break;
				default:
					throw new moojon_exception("Unsupported database driver ($db_driver)");
					break;
			}
			$db_dsn = '';
			foreach ($keys as $key => $value) {
				if (moojon_config::has($value)) {
					$db_dsn .= "$key=".moojon_config::key($value).';';
				}
			}
			$db_dsn = substr($db_dsn, 0, (strlen($db_dsn) - 1));
		}
		$db_username = moojon_config::key_or_null('db_username');
		$db_password = moojon_config::key_or_null('db_password');
		$db_driver_options = moojon_config::key_or_null('db_driver_options');
		$this->data = new PDO("$db_driver:$db_dsn", $db_username, $db_password, $db_driver_options);
		$this->data->setAttribute(self::ATTR_ERRMODE, self::ERRMODE_EXCEPTION);
	}
	
	static public function get() {
		if (!self::$instance) {
			self::$instance = new moojon_db();
		}
		return self::$instance;
	}
	
	static public function get_data() {
		$instance = self::get();
		return $instance->data;
	}
	
	static public function close() {
		if (self::$instance) {
			//$instance->data = null;
			//$instance = self::get();
		}
	}
	
	static public function begin_transaction() {
		$data = self::get_data();
		return $data->beginTransaction();
	}
	
	static public function commit() {
		$data = self::get_data();
		return $data->commit();
	}
	
	static public function error_code() {
		$data = self::get_data();
		return $data->errorCode();
	}
	
	static public function error_info() {
		$data = self::get_data();
		return $data->errorInfo();
	}
	
	static public function exec() {
		$data = self::get_data();
		return $data->exec();
	}
	
	static public function get_attribute($attribute) {
		$data = self::get_data();
		return $data->getAttribute($attribute);
	}
	
	static public function get_available_drivers() {
		$data = self::get_data();
		return $data->getAvailableDrivers();
	}
	
	static public function last_insert_id($name = null) {
		$data = self::get_data();
		return $data->lastInsertId($name);
	}
	
	static public function prepare($statement, $driver_options = array()) {
		$data = self::get_data();
		return $data->prepare($statement, $driver_options);
	}
	
	static public function query($statement) {
		$data = self::get_data();
		return $data->query($statement);
	}
	
	static public function quote($string, $parameter_type = PDO::PARAM_STR) {
		$data = self::get_data();
		return $data->quote($string, $parameter_type);
	}
	
	static public function roll_back() {
		$data = self::get_data();
		return $data->rollBack();
	}
	
	static public function set_attribute($attribute, $value) {
		$data = self::get_data();
		return $data->setAttribute($attribute, $value);
	}
	
	static public function create_table($table, $data, $options = null, $params = array()) {
		return self::run(self::prepare(moojon_db_driver::create_table($table, $data, $options)), $params);
	}
	
	static public function show_tables() {
		return self::run(self::prepare(moojon_db_driver::show_tables()));
	}
	
	static public function show_columns($table, $params = array()) {
		return self::run(self::prepare(moojon_db_driver::show_columns($table)), $params);
	}
	
	static public function drop_table($table, $params = array()) {
		return self::run(self::prepare(moojon_db_driver::drop_table($table)), $params);
	}
	
	static public function alter_table_rename($table, $data, $params = array()) {
		return self::run(self::prepare(moojon_db_driver::alter_table_rename($table, $data)), $params);
	}
	
	static public function add_column($table, $data, $params = array()) {
		return self::run(self::prepare(moojon_db_driver::add_column($table, $data)), $params);
	}
	
	static public function drop_column($table, $data, $params = array()) {
		return self::run(self::prepare(moojon_db_driver::drop_column($table, $data)), $params);
	}
	
	static public function change_column($table, $data, $params = array()) {
		return self::run(self::prepare(moojon_db_driver::change_column($table, $data)), $params);
	}
	
	static public function modify_column($table, $data, $params = array()) {
		return self::run(self::prepare(moojon_db_driver::modify_column($table, $data)), $params);
	}
	
	static public function add_index($table, $data, $options = null, $params = array()) {
		return self::run(self::prepare(moojon_db_driver::add_index($table, $data)), $params);
	}
	
	static public function drop_index($table, $data, $params = array()) {
		return self::run(self::prepare(moojon_db_driver::drop_index($table, $data)), $params);
	}
	
	static public function select($table, $data = null, $where = null, $order = null, $limit = null, $params = array()) {
		return self::run(self::prepare(moojon_db_driver::select($table, $data, $where, $order, $limit)), $params);
	}
	
	static public function insert($table, $data = null, $params = array()) {
		return self::run(self::prepare(moojon_db_driver::insert($table, $data)), $params);
	}
	
	static public function update($table, $data = null, $where = null, $params = array()) {
		return self::run(self::prepare(moojon_db_driver::update($table, $data, $where)), $params);
	}
	
	static public function delete($table, $where = null, $params = array()) {
		return self::run(self::prepare(moojon_db_driver::delete($table, $where)), $params);
	}
	
	static public function run(PDOStatement $statement, $params = array(), $fetch_style = self::FETCH_ASSOC) {
		$statement->execute($params);
		self::log($statement->queryString);
		if ($statement->rowCount()) {
			$statement->fetchAll($fetch_style);
		} else {
			return array();
		}
	}
}
?>