<?php

/**
 * @author Christoff Trüter <christoff@cstruter.com>
 * @version 1.0 
 * @copyright Copyright (c) 2011, CSTruter.com
 * @package MySQL
*/

/**
 * Wrapper class used for accessing MySQL
 * @package MySQL
*/
final class MySQL
{
	/**
	* @staticvar MySQL Singleton
	*/
	private static $instance;
	
	private $resource;

	/**
	* @var integer 
	*/
	private $count;
	
	/**
	* @var array Contains all parameters used for binding a query
	*/
	private $parameters = array();
	
	/**
	* @var string SQL Query
	*/
	private $query;
	
	/**
	* @var array Connection settings
	*/
	private $settings;
	
	/**
	* @param array $settings
	*/
	private function __construct(array $settings) {
		$this->settings = $settings;
	}
	
	/**
	* Instance & Resource available for queries
	* @return bool
	* @static
	*/
	private static function IsInstance()
	{
		return ((isset(self::$instance)) && (isset(self::$instance->resource)));
	}
	
	/**
	* @param string $host The MySQL Server
	* @param string $username
	* @param string $password
	* @param string $database
	* @param integer $port Option parameter - default 3306
	* @static
	*/
	private static function Config($host, $username, $password, $database, $port = 3306)
	{
		if (!function_exists('mysqli_connect')) {
			throw new Exception('MySQLI Extension not found!');
		}
		
		self::Close();
		self::$instance = new MySQL
		(
			array
			(
				'host'=> $host, 
				'username'=> $username,
				'password'=> $password, 
				'database'=> $database,
				'port'=> $port
			)		
		);
	}
	
	/**
	* Create a new SQL Query
	* @param string $query
	* @return MySQL
	*/
	public static function Create($query)
	{
		if (!isset(self::$instance))
			self::Config(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE); 
		self::$instance->query = $query;
		self::$instance->parameters = array();
		return self::$instance;
	}
	
	/**
	* Add parameter used for SQL Query
	* @param string $name
	* @param string|integer|bool|double|float $value
	* @param $type e.g. string|integer|boolean|double|float
	* @return MySQL
	*/
	public function Parameter($name, $value, $type = NULL) 
	{
		if (!isset($name))
			throw new Exception("Parameter name can't be null");
		
		if ($name[0] != '_')
		{
			if (!isset($this->parameters[$name]))
			{
				if (isset($type)) 
				{
					if (in_array($type, array('boolean', 'integer', 'float', 'double', 'string'))) {				
						settype($value, $type);
					} else {
						throw new Exception("Invalid type '$type' specified for parameter '$name'");
					}
				}
				else if (is_array($value) || is_object($value)) {
						throw new Exception("Invalid type specified for parameter '$name'");
				}
				if (is_string($value)) {
					$this->parameters[$name] = str_replace('?', '?_', $value);
				} else {
					$this->parameters[$name] = $value;
				}				
				return $this;
			}
			throw new Exception("Parameter $name already added");
		}
		throw new Exception("Parameter $name, not allowed to start with an underscore");
	}
	
	/**
	* Method used for insuring that queries are safe for execution
	*/
	private function Bind()
	{
		self::Open();
		$this->query = str_replace('?_', '?__', $this->query);

		foreach($this->parameters as $key=>$value)
		{
			if (is_string($value)) {
				$value = "'".$this->resource->real_escape_string($value)."'";
			} else if (is_bool($value)) {
				$value = ($value) ? 1 : 0;
			} else if (is_null($value)) {
				$value = 'NULL';
			}			
			$this->query = str_replace('?'.$key, $value, $this->query, $count);
			
			if ($count == 0) {
				throw new Exception("Parameter $key not found");
			}
		}
		$this->query = str_replace('?_', '?', $this->query);
	}
	
	/**
	 * Executes and sanatize a query that returns a set of rows e.g. select
	 * @return array
	*/		
	public function Query()
	{
		$this->Bind();
		$result = $this->resource->query($this->query, MYSQLI_USE_RESULT);
		
		$this->count = 0;
	
		if ($this->resource->error)
			throw new MySQLException($this->resource->error, $this->resource->errno);
		
		if (is_object($result))
		{	
			while ($row = $result->fetch_assoc()) 
			{
				if (!isset($rows)) {
					$rows = array();
				}
				$rows[] = $row;		
			}

			$this->count = $result->num_rows;
			
			$result->close();
		}
		return (isset($rows)) ? $rows : NULL;
	}
	
	/**
	 * Executes and sanatize a query that returns a single field/value e.g. count/sum
	 * @return integer
	*/		
	public function NonQuery()
	{
		$this->Bind();
		$result = $this->resource->query($this->query, MYSQLI_STORE_RESULT);
	
		if ($this->resource->error)
			throw new MySQLException($this->resource->error, $this->resource->errno);

		if (is_object($result)) {
			throw new MySQLException("Query returned result set, rather use Query() method");
		}
		return self::AffectedRows();
	}	

	/**
	 * Executes and sanatize a query that returns a single field/value e.g. count/sum
	 * @param $type e.g. string|integer|boolean|double|float
	 * @return integer
	*/	
	public function Scalar($type = NULL)
	{
		$this->Bind();
		$result = $this->resource->query($this->query);
	
		if ($this->resource->error)
			throw new MySQLException($this->resource->error, $this->resource->errno);
		
		if (is_object($result))
		{				
			if ($result->num_rows > 1) {
				throw new MySQLException("Query returned more than one result");
			}
			$row = $result->fetch_array(MYSQLI_NUM);
			$result->close();
		}
		$value = (isset($row)) ? $row[0] : NULL;
		
		if (isset($type)) 
		{
			if (in_array($type, array('boolean', 'integer', 'float', 'double', 'string'))) {				
				settype($value, $type);
			} else {
				throw new Exception("Invalid type '$type' specified for parameter '$name'");
			}
		}
		return $value;
	}

	/**
	 *  Get number of affected rows in previous MySQL operation
	 * @return integer
	 * @static
	*/	
	public static function AffectedRows()
	{
		if (self::IsInstance()) {
			return self::$instance->resource->affected_rows;
		}
	}

	/**
	 * Get number of rows in result
	 * @return integer 
	 * @static
	*/	
	public static function Count()
	{
		if (self::IsInstance()) {
			return self::$instance->count;
		}
	}

	/**
	 * Get the ID generated in the last query
	 * @return integer
	 * @static
	*/	
	public static function LastId()
	{
		if (self::IsInstance()) {
			return self::$instance->resource->insert_id;
		}
	}
	
	/**
	 * Open a connection to a MySQL Server
	 * @static
	*/	
	public static function Open()
	{
		if (!self::IsInstance()) {
			self::$instance->resource = @new mysqli(self::$instance->settings['host'], self::$instance->settings['username'], 
											self::$instance->settings['password'], self::$instance->settings['database'], 
											self::$instance->settings['port']);
					
			if (mysqli_connect_errno())	
			{
				$errno = mysqli_connect_errno();
				unset(self::$instance->resource);
				throw new MySQLException(mysqli_connect_error(), $errno);
			}
		}
	}
	
	/**
	 * Close MySQL connection
	 * @static
	*/	
	public static function Close()
	{
		if (self::IsInstance()) {
			self::$instance->resource->close();
		}
	}
	
	public function __destruct() {
		self::Close();
	}
}

/**
 * Exception class for throwing MySQL Exceptions
 * @package MySQL
 * @subpackage Exceptions
*/
class MySQLException extends Exception 
{
	/**
	* @var integer
	*/
	public $errorno;
	
	public function __construct($message, $errorno = NULL)
	{
		parent::__construct($message);
		$this->errorno = $errorno;
	}
}

?>