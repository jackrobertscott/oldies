<?php
Class AjaxAddPref
{
	function addPref()
	{
		session_start();
		require('../../../../db-dets.php');
		$return = array();
		$return['message'] = "";
		$return['optArray'] = "";
		$courseId = @$_POST['courseId'];
		$courseId = substr($courseId, strlen("unitId_"));
		$return['courseId'] = $courseId;
		$id = $_SESSION['Id'];
		if(empty($id)){$return['message'] .= "SESSION ID NOT SET.";}
		if(empty($courseId)){$return['message'] .= "COURSE ID FIELD EMPTY.";}
		if($return['message'] == "")
		{
			$query = "SELECT CourseJSONObj FROM Users WHERE Id = '$id'";
			if($result = $mysqli->query($query))
			{
				$row = $result->fetch_assoc();
				$tempArray = json_decode($row['CourseJSONObj'], true);
				if(!in_array($courseId, $tempArray) && count($tempArray) < 6)
					$tempArray[] = $courseId;
				$tempArraystr = json_encode($tempArray);
				$query = "UPDATE Users SET CourseJSONObj = '$tempArraystr' WHERE Id = '$id'";
				$result->free();
				if(!$mysqli->query($query))
				{
					$return['message'] = "QUERY TO SERVER FAILED: " . $mysqli->error;
				}else{
					if(is_array($tempArray))
					{
						foreach($tempArray as $cid)
						{
							$query = "SELECT CourseCode, Id FROM Courses WHERE Id = '$cid'";
							if($result = $mysqli->query($query))
							{
								while($row = $result->fetch_assoc())
								{
									$optArray[] = $row;
								}
								$return['optArray'] = json_encode($optArray);
								$result->free();
							}else{
								$return['message'] = "QUERY TO SERVER FAILED: " . $mysqli->error;
							}
						}
					}
				}
			}else{
				$return['message'] = "QUERY TO SERVER FAILED: " . $mysqli->error;
			}
		}
		$mysqli->close();
		return json_encode($return);
	}
}

$AjaxAddPref = new AjaxAddPref();
echo $AjaxAddPref->addPref();
?>