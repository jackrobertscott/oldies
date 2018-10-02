<?php 
/**
*@author Jack Scott
*@version v1.0 7/14
*/
class Base
{
	protected $data;
	protected $errors;
	protected $prefixErr;

	function __construct()
	{
		//Initialise Variables
		$this->data = array();
		$this->errors = array();
		$this->prefixErr = "The following error occured: ";
	}

	/**
	*This sets the objects data array variable equal to the information in the specified db table
	*each variable in table should be accessable through $this->get('Example');
	*
	*@param $table = String table name
	*@param $id = Int value of user Id
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	protected function setData($table, $id = null)
	{
		if(empty($id))
		{
			$this->errors[] = "Id has not been set. Please Log In.";
			return false;
		}
		global $mysqli;
		$query = "SELECT * FROM $table WHERE Id = '$id'";
		if($result = $mysqli->query($query))
		{
			$assoc = $result->fetch_assoc();
			$this->data = $assoc;
			$result->free();
		}else{
			$this->errors[] = $this->prefixErr . $mysqli->error;
			return false;
		}
		return true;
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
	public function dbUpdate($table, $args, $id = null)
	{
		global $mysqli;
		foreach ($args as $key => $value) {
			$query = "UPDATE $table SET $key = '$value' WHERE Id = '$id'";
			if(!$mysqli->query($query))
			{
				$this->errors[] = $this->prefixErr . $mysqli->error;
				return false; 
			}
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
	public function inUse($table, $key, $value)
	{
		global $mysqli;
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
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function dbInsert($table, $array)
	{
		global $mysqli;
		$keyStr = '';
		$valueStr = '';
		$i = 0;
		if(is_array($array))
		{
			foreach($array as $key => $value)
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
		}
		$query = "INSERT INTO $table ($keyStr)
				  VALUES ($valueStr)";
		if(!$mysqli->query($query))
		{
			$this->errors[] = $this->prefixErr . $mysqli->error;
			return false;
		}
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
	*Get variables from data array
	*
	*@param $data key to get from data[] array
	*@return $data = String variable
	*/
	public function get($var)
	{
		if(is_array($this->data) && !empty($this->data))
			return $this->data[$var];
		return false;
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