<?php
/**
*@author Jack Scott
*@version v1.0 7/14
*/
class Course extends Base
{
	const TABLENAME = "Courses";

	function __construct(){}

	/**
	*this checks if the users submited email adress is available 
	*return true if not in use and false if in use already
	*
	*@param $email = String email
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	private function checkCourseCode($code = '')
	{
		return $this->inUse(self::TABLENAME, "CourseCode", $code);
	}

	/**
	*This function inserts a new course
	*
	*@param $code = String(8), $name = String course name, $desc = String description, $userId = Int User Id
	*@return boolean = success/failure
	*@see all errors into $this->errors array; access via getErrors()
	*/
	public function dbInsertCourse($code, $name, $uni, $userId)
	{
		$message = $this->checkCourseCode($code);
		if(!$this->checkCourseCode($code))
			return false;
		$insArray = array();
		$insArray['CourseCode'] = $code;
		$insArray['CourseName'] = $name;
		$insArray['University'] = $uni;
		$insArray['CreatorId'] = $userId;
		return parent::dbInsert(self::TABLENAME, $insArray);
	}

}
?>