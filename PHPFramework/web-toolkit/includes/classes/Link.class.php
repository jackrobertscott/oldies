<?php
/**
*DB row made to link two different table row Id's
*
*@author Jack Scott
*@version v1.0 7/14
*/
class Link extends Base
{
	/**
	*Constructor
	*
	*@param $table = table of the database for link
	*/
	function __construct($table)
	{
		parent::__construct();
		if(empty($table))
			$this->errors[] = "Table must be specified when creating an activity.";
		$this->table = $table;
	}

	/**
	*@param $unitId = the id of the unit wish to link
	*@param $id = the id of user account wished to link
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function create($unitId, $id = null)
	{
		global $mysqli;
		if(!empty($this->table))
		{
			$this->errors = "Table not specified."
			return false;
		}
		if(empty($id))
		{
			$this->errors[] = "Id parameter not provided.";
			return false;
		}
		if(empty($unitId))
		{
			$this->errors[] = "Not all parameters provided to function.";
			return false;
		}
		$query = "UPDATE $this->table SET Active = 1 WHERE (UserId = $id AND UnitId = $unitId) OR (UserId = $unitId AND UnitId = $id)";
		if(!$mysqli->query($query))
		{
			$this->errors[] = $this->prefixErr . $mysqli->error;
			return false; 
		}else{
			if($mysqli->affected_rows > 0)
				return true;
		}
		//If the row doesn't exist, build it...
		$args = array(
		"UserId" => $id,
		"UnitId" => $unitId
		);
		if(!$this->id = $this->dbInsert($args))
			return false;
		return true;
	}

	/**
	*Ignore a friend request
	*
	*@param $sId = Id of first user
	*@param $rId = Id of second user
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function delete($unitId, $id = null)
	{
		global $mysqli;
		if(!empty($this->table))
		{
			$this->errors = "Table not specified."
			return false;
		}
		$query = "DELETE FROM $this->table WHERE (UserId = $id AND UnitId = $unitId) OR (UserId = $unitId AND UnitId = $id)";
		if(!$myqli->query($query))
		{
			$this->errors[] = $this->prefixErr . $mysqli->error;
			return false; 
		}
		return true;
	}
}
?>