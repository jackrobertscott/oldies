<?php
Class AjaxHour
{
	function hourProcess()
	{
		session_start();
		require('../../../../db-dets.php');
		$return = array();
		$return['message'] = "";
		$unit = @$_POST['unitCode'];
		$uni = @$_POST['uniId'];
		$courseId = @$_POST['courseId'];
		$courseCode = @$_POST['courseCode'];
		$id = $_SESSION['Id'];
		$unit = substr($unit, strlen("unitCode_"));
		$courseCode = substr($courseCode, strlen("courseCode_"));
		$uni = substr($uni, strlen("uniId_"));
		$courseId = substr($courseId, strlen("courseId_"));
		if(empty($unit)){$return['message'] .= "UNIT POST FIELD EMPTY.";}
		if(empty($uni)){$return['message'] .= "UNIVERSITY FIELD EMPTY.";}
		if(empty($courseCode)){$return['message'] .= "COURSE CODE FIELD EMPTY.";}
		if(empty($courseId)){$return['message'] .= "COURSE Id FIELD EMPTY.";}
		if(empty($id)){$return['message'] .= "SESSION ID NOT SET.";}
		if($return['message'] == "")
		{
			$query = "SELECT Id FROM Preferences WHERE UserId = '$id' AND UnitCode = '$unit' AND CourseId = '$courseId'";
			if(!$mysqli->query($query))
			{
				$return['message'] = "QUERY TO SERVER FAILED: " . $mysqli->error;
			}else{
				if($mysqli->affected_rows > 0)
				{
					$unit_query = "DELETE FROM Preferences WHERE UserId = '$id' AND UnitCode = '$unit'";
					if(!$mysqli->query($unit_query))
					{
						$return['message'] = "QUERY TO SERVER FAILED: " . $mysqli->error;
					}else{
						$return['status'] = "deleted";
					}
				}else{
					$unit_query = "DELETE FROM Preferences WHERE UserId = '$id' AND UnitCode = '$unit'";
					if(!$mysqli->query($unit_query))
					{
						$return['message'] = "QUERY TO SERVER FAILED: " . $mysqli->error;
					}else{
						$query = "INSERT INTO Preferences (UnitCode, UserId, CourseId, CourseCode, UniversityId) 
										VALUES ('$unit', '$id', '$courseId', '$courseCode', '$uni')";
						if(!$mysqli->query($query))
						{
							$return['message'] = "QUERY TO SERVER FAILED: " . $mysqli->error;
						}else{
							$return['status'] = "inserted";
						}
					}
				}
			}
		}
		$mysqli->close();
		return json_encode($return);
	}
}

$Ajaxhour = new AjaxHour();
echo $Ajaxhour->hourProcess();
?>