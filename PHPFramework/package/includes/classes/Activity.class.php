<?php
/**
*@author Jack Scott
*@version v1.0 7/14
*/
class Activity extends Base
{
	function __construct($table, $id = null)
	{
		parent::__construct($id);
		$this->table = $table;
		if(empty($this->table))
			$this->errors[] = "Table must be specified when creating an activity.";
	}

	/**
	*Add a Post to the database
	*
	*@param $args = key -> value arguements to place in db. $key = column name
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function create($userId, $message, $extras = null)
	{
		$args = array(
		"UserId" => $userId,
		"Message" => $message,
		"Time" => date('jS M Y, g:i a'),
		"Active" => 1
		);
		if(is_array($extras))
			$args = array_merge($args, $extras);
		if(!$this->id = $this->dbInsert($args))
			return false;
		return true;
	}

	/**
	*Increments the 'Like' column for a row by one
	*
	*@param $postId = the posts Id
	*@param $userId = the users Id
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function like($postId = null, $userId = null)
	{
		if(empty($postId))
		{
			if(empty($this->id))
			{
				$this->errors[] = "Post Id parameter not provided.";
				return false;
			}
			$postId = $this->id;	
		}
		if(empty($userId))
		{
			if(empty($_SESSION['UserId']))
			{
				$this->errors[] = "Please Log in.";
				return false;
			}
			$userId = $_SESSION['UserId'];
		}
		$jsonLikes = $this->get("Likes", $postId);
		if(!empty($this->errors))
			return false;
		$result = json_decode($jsonLikes);
		if(in_array($userId, $result))
		{
			$newArray = array();
			foreach($result as $value)
			{
				if($value != $userId)
					$newArray[] = $value;
			}
			$result = $newArray;
		}else{
			$result[] = $userId;
		}
		$args = array(
		"Likes" => json_encode($result)
		);
		return $this->dbUpdate($args, $postId);
	}

	/**
	*Increments the 'Dislike' column for a row by one
	*
	*@param $postId = the posts Id
	*@param $userId = the users Id
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function dislike($postId = null, $userId = null)
	{
		if(empty($postId))
		{
			if(empty($this->id))
			{
				$this->errors[] = "Post Id parameter not provided.";
				return false;
			}
			$postId = $this->id;	
		}
		if(empty($userId))
		{
			if(empty($_SESSION['UserId']))
			{
				$this->errors[] = "Please Log in.";
				return false;
			}
			$userId = $_SESSION['UserId'];
		}
		$jsonLikes = $this->get("Dislikes", $postId);
		if(!empty($this->errors))
			return false;
		$result = json_decode($jsonLikes);
		if(in_array($userId, $result))
		{
			$newArray = array();
			foreach($result as $value)
			{
				if($value != $userId)
					$newArray[] = $value;
			}
			$result = $newArray;
		}else{
			$result[] = $userId;
		}
		$args = array(
		"Dislikes" => json_encode($result)
		);
		return $this->dbUpdate($args, $postId);
	}

	/**
	*Set Active = 0
	*
	*@param $id = optional id of the row
	*@return boolean = success/failure
	*/
	public function remove($id = null)
	{
		if(empty($id))
		{
			if(empty($this->id))
			{
				$this->errors[] = "Id parameter not provided.";
				return false;
			}
			$id = $this->id;
		}
		return $this->deactivate($this->table, $id);
	}

}
?>