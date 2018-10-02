<?php
/**
*@author Jack Scott
*@version v1.0 7/14
*/
class Image extends Base
{
	const UPLOADFILE = "uploads";

	private $location;
	private $ImgArray;
	private $extention;

	function __construct()
	{
		$this->location = "NOTSET";
		$this->ImgArray = null;
		$this->extention = "NOTSET";
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
	public function upImg($name, $dirFolder, $nw, $nh)
	{
		$this->ImgArray = $_FILES[$name];
		$this->extention = strtolower(end(explode('.' , $this->ImgArray['name'])));
		if(!$this->checkImg())
			return false; 
		//Should load the images outside of html file
		$imgDir = self::UPLOADFILE."/$dirFolder/";
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
		if($this->ImgArray['error'] > 0)
		{
			$this->errors[] = "An error has occured. Image was unable to be uploaded.";
			return false; 
		}
		if(!in_array($this->extention, $exts))
			$this->errors[] = "Image is of an incompatable type (png, jpg, jpeg).";
		if($this->ImgArray['size'] > 2000000)
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
		list($upw, $uph) = getimagesize($this->ImgArray['tmp_name']);
		list($width, $height) = $this->dimensions($upw, $uph, $nw, $nh);
		$imgCanvas = imagecreatetruecolor($width, $height);
		switch($this->extention){
			case 'jpg':
			case 'jpeg':
				$imgCopy = imagecreatefromjpeg($this->ImgArray['tmp_name']);
				break;
			case 'png':
				$imgCopy = imagecreatefrompng($this->ImgArray['tmp_name']);
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
	*@return the files directory
	*/
	public function getLocation()
	{
		return $this->location;
	}

}
?>