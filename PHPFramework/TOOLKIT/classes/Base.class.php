<?php 
/**
*@author Jack Scott
*@version v1.0 7/14
*/
class Base
{
	protected $errors;
	protected $prefixErr;
	protected $id;
	protected $table;

	function __construct($id = null, $table = null)
	{
		$this->id = $id;
		$this->table = $table;
		$this->data = array();
		$this->errors = array();
		$this->prefixErr = "The following error occured: ";
	}

	/**
	*Inserts an array of key => value pairs and puts them in a table
	*
	*@param $table = String table name
	*@param $args = array("String" => $value) to update
	*@param $id = Int value of user Id
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function dbUpdate($args, $id = null, $table = null)
	{
		global $mysqli;
		if(!is_array($args))
		{
			$this->errors[] = "Array parameter not provided.";
			return false;
		}
		if(empty($table))
		{
			if(empty($this->table))
			{
				$this->errors[] = "Table parameter not provided.";
				return false;
			}
			$table = $this->table;
		}
		if(empty($id))
		{
			if(empty($this->id))
			{
				$this->errors[] = "Id parameter not provided.";
				return false;
			}
			$id = $this->id;
		}
		$query = "UPDATE $table SET ";
		$i = 0;
		foreach($args as $key => $value){
			if($i>0) $query .= ", ";
			$query .= "$key = '$value'";
			$i++;
		}
		$query .= " WHERE Id = '$id'";
		if(!$mysqli->query($query))
		{
			$this->errors[] = $this->prefixErr . $mysqli->error;
			return false; 
		}
		return true;
	}

	/**
	*this checks if the users submited email adress is available 
	*return true if not in use and false if in use already
	*
	*@param $table = db table name
	*@param $key = db key
	*@param $value = any variable
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function inUse($key, $value, $table = null)
	{
		global $mysqli;
		if(empty($key)||empty($value))
		{
			$this->errors[] = "Not all parameters provided to function.";
			return false;
		}
		if(empty($table))
		{
			if(empty($this->table))
			{
				$this->errors[] = "Table parameter not provided.";
				return false;
			}
			$table = $this->table;
		}
		$table = $this->table;
		$query = "SELECT Id FROM $table WHERE $key = '$value'";
		if(!$mysqli->query($query))
		{
			$this->errors[] = $this->prefixErr . $mysqli->error;
			return false;
		}else{
			if($mysqli->affected_rows > 0)
			{
				$this->errors[] = $key . " already exists.";
				return false; 
			}
		}
		return true;
	}

	/**
	*Inserts an array of key => value pairs and puts them in a table
	*
	*@param $table = table name
	*@param $array = array of key => value pairs
	*@return boolean = success/failure -> success = Insert Id
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function dbInsert($args, $table = null)
	{
		global $mysqli;
		if(!is_array($args))
		{
			$this->errors[] = "Array parameter not provided.";
			return false;
		}
		if(empty($table))
		{
			if(empty($this->table))
			{
				$this->errors[] = "Table parameter not provided.";
				return false;
			}
			$table = $this->table;
		}
		$table = $this->table;
		$keyStr = '';
		$valueStr = '';
		$i = 0;
		foreach($args as $key => $value)
		{
			if($i != 0)
			{
				$keyStr .= ", ";
				$valueStr .= ", ";
			}
			$i++;
			$keyStr .= $key;
			$valueStr .= "'$value'";
		}
		$query = "INSERT INTO $table ($keyStr)
				  VALUES ($valueStr)";
		if(!$mysqli->query($query))
		{
			$this->errors[] = $this->prefixErr . $mysqli->error;
			return false;
		}
		return $mysqli->insert_id;
	}

	/**
	*Get the value from database corresponding to table, key and id
	*
	*@param $data key to get from data[] array
	*@return $data = String variable
	*/
	public function get($key, $id = null, $table = null)
	{
		global $mysqli;
		if(empty($key))
		{
			$this->errors[] = "Not all parameters provided to function.";
			return false;
		}
		if(empty($table))
		{
			if(empty($this->table))
			{
				$this->errors[] = "Table parameter not provided.";
				return false;
			}
			$table = $this->table;
		}
		if(empty($id))
		{
			if(empty($this->id))
			{
				$this->errors[] = "Id parameter not provided.";
				return false;
			}
			$id = $this->id;
		}
		$query = "SELECT $key FROM $table WHERE Id = '$id' AND Active = 1";
		if($result = $mysqli->query($query))
		{
			$assoc = $result->fetch_assoc();
			$value = $assoc[$key];
			$result->free();
		}else{
			$this->errors[] = $this->prefixErr . $mysqli->error;
			return false;
		}
		return $value;
	}

	/**
	*Set Active = 0
	*If id not given, will make $id = $this->id
	*
	*@param $table = table to access
	*@param $id = id of the row to deactivate
	*@return boolean = success/failure
	*/
	public function activate($id = null, $table = null)
	{
		if(empty($table))
		{
			if(empty($this->table))
			{
				$this->errors[] = "Table parameter not provided.";
				return false;
			}
			$table = $this->table;
		}
		if(empty($id) && !empty($this->id))
			$id = $this->id;
		$args = array(
		"Active" => 1
		);
		if(!$this->dbUpdate($args));
			return false; 
		return true;
	}

	/**
	*Set Active = 0
	*If id not given, will make $id = $this->id
	*
	*@param $table = table to access
	*@param $id = id of the row to deactivate
	*@return boolean = success/failure
	*/
	public function deactivate($id = null, $table = null)
	{
		if(empty($table))
		{
			if(empty($this->table))
			{
				$this->errors[] = "Table parameter not provided.";
				return false;
			}
			$table = $this->table;
		}
		if(empty($id) && !empty($this->id))
			$id = $this->id;
		$args = array(
		"Active" => 0
		);
		if(!$this->dbUpdate($args));
			return false; 
		return true;
	}

	/**
	*Sanatise the entire $_POST array. 
	*access method via Base::sanPOST();
	*
	*@return boolean = success/failure
	*@see array elements are NOT checked if empty
	*/
	public static function sanPOST()
	{
		if(is_array($_POST))
		{
			foreach($_POST as $key => $value) 
			{
				$_POST[$key] = self::escape($value);
			}
		}
		return true;
	}

	/**
	*Sanatise the entire $_GET array. 
	*access method via Base::sanGET();
	*
	*@return boolean = success/failure
	*@see array elements are NOT checked if empty
	*/
	public static function sanGET()
	{
		if(is_array($_GET))
		{
			foreach($_GET as $key => $value) 
			{
				$_GET[$key] = self::escape($value);
			}
		}
		return true;
	}

	/**
	*Sanatise a given string
	*
	*@param $str = String to be sanatised
	*@return $str = sanatised string
	*/
	public static function escape($str)
	{
		global $mysqli;
		return strip_tags($mysqli->real_escape_string(trim($str)));
	}

	/**
	*Return the id of object if set, else return false
	*
	*@return $error[] array
	*/
	public function getId()
	{
		if(!empty($this->id))
			return $this->id;
		return null;
	}

	/**
	*Return the error array
	*
	*@return $error[] array
	*/
	public function getErrors()
	{
		return $this->errors;
	}

}
?>