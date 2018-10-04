<?php
/**
*DB row [must include a message and user Id]
*Extras can be added via parameter array - see create()
*
*@author Jack Scott
*@version v1.0 7/14
*/
class Post extends Base
{

	/**************************************************************
	******* Must specify TABLE when creating 'Post' object ********
	**************************************************************/

	/**
	*Constructor
	*
	*@param $table = table of the database for link
	*/
	function __construct($table, $id = null)
	{
		parent::__construct($id);
		$this->table = $table;
		if(empty($this->table))
			$this->errors[] = "Table must be specified when creating a Post object.";
	}

	/**
	*Add a Post to the database
	*
	*@param $message = column name
	*@param $userId = the users id
	*@param $extras = key -> value arguements to place in db
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function create($message = "", $extras = null, $userId = null)
	{
		if(empty($userId))
		{
			if(empty($_SESSION['UserId']))
			{
				$this->errors[] = "Please Log in.";
				return false;
			}
			$userId = $_SESSION['UserId'];
		}
		$args = array(
		"UserId" => $userId,
		"Message" => $message,
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

}
?>