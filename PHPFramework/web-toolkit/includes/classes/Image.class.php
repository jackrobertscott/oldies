<?php
/**
*@author Jack Scott
*@version v1.0 7/14
*/
class Image extends Base
{
	private $location;
	private $imgArray;
	private $extention;

	/**
	*Constructor
	*
	*@param $id = [optional] id of a Image row id
	*/
	function __construct($id = null)
	{
		parent::__construct($id);
		$this->location = null;
		$this->imgArray = null;
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
		$imgDir = UPLOADFILE."/$dirFolder/";
		$imgName = "IMG_" . substr(SHA1(microtime() . rand()), 0, 10) . "_" . time() . ".jpeg";
		while(file_exists($imgDir.$imgName))
			$imgName = "IMG_" . substr(SHA1(microtime() . rand()), 0, 10) . "_" . time() . ".jpeg";
		$this->location = $imgDir.$imgName;
		$this->copyAndAdd($nw, $nh);
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
	*Add the image to the database
	*
	*@param $userID = int; users Id
	*@param $isPost = Boolean; is image uploaded with post
	*@param $location = Sting; location of image
	*@param $extras = key -> value arguements to place in db
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function create($userId, $isPost, $location, $extras = null)
	{
		global $mysqli;
		$args = array(
		"UserId" => $userId,
		"Location" => $location,
		"Time" => date('jS M Y, g:i a'),
		"Active" => 1
		);
		$args['IsPost'] = ($isPost)? 1: 0;
		if(is_array($extras))
			$args = array_merge($args, $extras);
		if(!$this->id = $this->dbInsert($args))
			return false;
		$this->id = $mysqli->insert_id;
		return true;
	}

	/**
	*@return the images directory location
	*/
	public function getLocation()
	{
		return $this->location;
	}

	/**
	*@return the images db row id
	*/
	public function getImageId()
	{
		return $this->id;
	}

	/**
	*Set Active = 0
	*
	*@param $id = optional id of the row
	*@return boolean = success/failure
	*/
	public function remove($id = null)
	{
		return $this->deactivate($this->table, $id);
	}

}
?>