<?php
/**
*@author Jack Scott
*@version v1.0 7/14
*/
class Image extends Base
{
	protected $location;
	protected $imgArray;
	protected $extention;

	/**
	*Constructor
	*
	*@param $id = [optional] id of a Image row id
	*/
	function __construct($id = null)
	{
		parent::__construct($id);
		$this->extention = "NOTSET";
		$this->table = TABLE_IMAGES;
	}

	/**
	*Checks an Image file, resizes it and then uploads it. 
	*
	*@param $fwN = $_FILE['inputname'] array with all inputs data
	*@param $name = name of [input] to access correct image in $_FILE array
	*@param $dirFN = (within uploads file) name of file for save direction
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function upload($name, $dirFolder, $nw, $nh)
	{
		$this->imgArray = $_FILES[$name];
		$this->extention = strtolower(end(explode('.' , $this->imgArray['name'])));
		if(!$this->checkImg())
			return false; 
		//Should load the images outside of html file
		$imgDir = "../".UPLOADFILE."/$dirFolder/";
		$imgName = "IMG_" . substr(SHA1(microtime() . rand()), 0, 10) . "_" . time() . ".jpeg";
		while(file_exists($imgDir.$imgName))
			$imgName = "IMG_" . substr(SHA1(microtime() . rand()), 0, 10) . "_" . time() . ".jpeg";
		$this->location = $imgDir.$imgName;
		$this->copyAndAdd($nw, $nh);
		return true;
	}

	/**
	*Add the image to the database
	*
	*@param $extras = key -> value arguements to place in db
	*@param $userID = users Id
	*@param $location = location of image
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function create($extras = null, $userId = null, $location = null)
	{
		global $mysqli;
		if(empty($userId))
		{
			if(empty($_SESSION['UserId']))
			{
				$this->errors[] = "Please Log in.";
				return false;
			}
			$userId = $_SESSION['UserId'];
		}
		if(empty($location))
		{
			if(empty($this->location))
			{
				$this->errors[] = "A location must be given to add to database.";
				return false;
			}
			$location = $this->location;
		}
		//add image to Images data table
		$args = array(
		"UserId" => $userId,
		"Location" => $location,
		"Active" => 1
		);
		if(is_array($extras))
			$args = array_merge($args, $extras);
		if(!$this->id = $this->dbInsert($args))
			return false;
		$this->id = $mysqli->insert_id;
		return true;
	}

	/**
	*Add display image
	*Create image
	*Link image to the user
	*
	*@param $extras = key -> value arguements to place in db
	*@param $userID = users Id
	*@param $location = location of image
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function addDisplayImage($table, $extras = null, $userId = null, $location = null)
	{
		if(empty($userId))
			$userId = $_SESSION['UserId'];
		//upload image file
		if(!$this->create($extras, $userId, $location))
			return false;
		//add new dp tuple
		$link = new Link($table, $this->id, $userId, false);
		$link->deactivateLinksWithId($userId, 1);
		$link->create();
		$this->errors = array_merge($link->getErrors());
		if(!empty($this->errors))
			return false; 
		return true;
	}

	/**
	*Check values of file are allowed on upload
	*
	*@param $_FILE = array of all image data
	*@param $name = name of [input] to access correct image in $_FILE array
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	private function checkImg()
	{
		$exts = array('png','jpg','jpeg');
		if($this->imgArray['error'] > 0)
		{
			$this->errors[] = "Image was unable to be uploaded.";
			return false; 
		}
		if(!in_array($this->extention, $exts))
			$this->errors[] = "Image is of an incompatable type (png, jpg, jpeg).";
		if($this->imgArray['size'] > 2000000)
			$this->errors[] = "Image is beyond the maximum file size (2MB).";
		if(!empty($this->errors))
			return false; 
		return true;
	}

	/**
	*Get correct dimensions for the image and then create new image to be uploaded
	*
	*@param $nh = new height wanted of image
	*@param $nw = new width wanted of image
	*@return boolean = success/failure
	*/
	private function copyAndAdd($nw, $nh)
	{
		list($upw, $uph) = getimagesize($this->imgArray['tmp_name']);
		list($width, $height) = $this->dimensions($upw, $uph, $nw, $nh);
		$imgCanvas = imagecreatetruecolor($width, $height);
		switch($this->extention){
			case 'jpg':
			case 'jpeg':
				$imgCopy = imagecreatefromjpeg($this->imgArray['tmp_name']);
				break;
			case 'png':
				$imgCopy = imagecreatefrompng($this->imgArray['tmp_name']);
				break;
		}
		imagecopyresampled($imgCanvas, $imgCopy, 0, 0, 0, 0, $width, $height, $upw, $uph);
		imagejpeg($imgCanvas, $this->location, 100);
		imagedestroy($imgCanvas);
		imagedestroy($imgCopy);
		return true;
	}

	/**
	*Prepare new dimensions based on wether img is potrait or landscape
	*
	*@param $height = uploaded height of image
	*@param $width = uploaded width of image
	*@param $nh = new height wanted of image
	*@param $nw = new width wanted of image
	*@return list($width, $height) = new image and height of image to upload
	*/
	private function dimensions($upw, $uph, $nw, $nh)
	{
		$nRatio = ($nw/$nh);
		$imgRatio = ($upw/$uph);
		if($nRatio>$imgRatio)
		{
			return array($nw, ($nw/$imgRatio));
		}else{
			return array(($nh*$imgRatio), $nh);
		}
	}

	/**
	*@return the images directory location
	*/
	public function getLocation()
	{
		return $this->location;
	}

}
?>