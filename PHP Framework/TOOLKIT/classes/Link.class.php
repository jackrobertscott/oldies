<?php
/**
*DB row made to link two different table row Id's
*
*@author Jack Scott
*@version v1.0 7/14
*/
class Link extends Base
{

	/**************************************************************
	* Must create NEW 'Link' object: For every combination of Ids *
	******* Must specify TABLE when creating 'Link' object ********
	**************************************************************/

	protected $oneId;
	protected $twoId;

	/**
	*Constructor
	*
	*@param $table = table of the database for link
	*@param $oneId = primary Id
	*@param $twoId = secondary Id, if left unspecified; Users Id will used
	*/
	function __construct($table, $oneId, $twoId = null, $addDB = true)
	{
		parent::__construct();
		if(empty($table))
			$this->errors[] = "Table name has not been specified.";
		$this->table = $table;
		if(empty($oneId))
			$this->errors[] = "Primary Id has not been specified.";
		if(empty($twoId))
		{
			if(empty($_SESSION['UserId']))
			{
				$this->errors[] = "Secondary Id is not specified. User must log in.";
			}else{
				$twoId = $_SESSION['UserId'];
			}
		}
		$this->oneId = $oneId;
		$this->twoId = $twoId;
		if(empty($this->errors))
			if(!$this->id = $this->exists($oneId, $twoId))
				if(empty($this->errors) && $addDB)
					$this->create($oneId, $twoId);	
	}

	/**
	*Use this function for links between tables that do not include the Users Id.
	*
	*@param $oneId = Id of primary unit
	*@param $oneId = Id of secondary unit
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function create($oneId = null, $twoId = null)
	{
		if(empty($this->table))
		{
			$this->errors = "Table not specified.";
			return false;
		}
		if(empty($oneId) || empty($twoId))
		{
			if(!empty($this->oneId) && !empty($this->twoId))
			{
				$oneId = $this->oneId;
				$twoId = $this->twoId;
			}else{
				$this->errors[] = "Not all required Id parameters provided.";
				return false;
			}
		}
		if($this->exists($oneId, $twoId))
			return true;
		if(!empty($this->errors))
			return false;
		$args = array(
		"PrimaryId" => $oneId,
		"SecondaryId" => $twoId,
		"Active" => 1
		);
		if(!$this->id = $this->dbInsert($args))
			return false;
		return true;
	}

	/**
	*Check if the Link Exists
	*
	*@param $oneId = Id of primary unit
	*@param $oneId = Id of secondary unit
	*@return int = Link Id, false otherwise
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function exists($oneId = null, $twoId = null)
	{
		global $mysqli;
		if(empty($this->table))
		{
			$this->errors = "Table not specified.";
			return false;
		}
		if(empty($oneId) && !empty($this->oneId))
		{
			$oneId = $this->oneId;
		}else{
			$this->errors[] = "Not all required Id parameters provided. (1)";
			return false;
		}
		if(empty($twoId) && !empty($this->twoId))
		{
			$twoId = $this->twoId;
		}else{
			$this->errors[] = "Not all required Id parameters provided. (2)";
			return false;
		}
		$query = "SELECT Id FROM $this->table WHERE (PrimaryId = $oneId AND SecondaryId = $twoId) AND Active = 1";
		if(!$result = $mysqli->query($query))
		{
			$this->errors[] = $this->prefixErr . $mysqli->error;
			return false; 
		}
		if($mysqli->affected_rows > 0)
		{
			$row = $result->fetch_assoc();
			return $row['Id'];
		}
		return false;
	}

	/**
	*Check if the Link Exists
	*
	*@param $id = The id of which the to be deactivated links include
	*@param $column = (int) primary or secondary column (0 or 1)
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function deactivateLinksWithId($id, $column, $table = null)
	{
		global $mysqli;
		switch ($column) 
		{
			case 0:
				$value = 'PrimaryId';
				break;
			case 1:
				$value = 'SecondaryId';
				break;
		}
		if(empty($table))
		{
			if(empty($this->table))
			{
				$this->errors = "Table not specified.";
				return false;
			}
			$table = $this->table;
		}
		$query = "UPDATE $table SET Active = 0 WHERE $value = $id";
		if(!$mysqli->query($query))
		{
			$this->errors[] = $this->prefixErr . $mysqli->error;
			return false; 
		}
		return true;
	}

}
?>