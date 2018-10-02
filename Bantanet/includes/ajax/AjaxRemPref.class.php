<?php
Class AjaxRemPref
{
	function remPref()
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
				$newArray = json_decode($row['CourseJSONObj'], true);
				if(!empty($newArray))
				{
					$tempArray = array();
					if(is_array($newArray))
					{
						foreach($newArray as $cid)
						{
							if($cid != $courseId)
								$tempArray[] = $cid;
						}
					}
					$tempArraystr = json_encode($tempArray);
					$query = "UPDATE Users SET CourseJSONObj = '$tempArraystr' WHERE Id = '$id'";
					if(!$mysqli->query($query) || !$mysqli->query("DELETE FROM Preferences WHERE UserId = '$id' AND CourseId = '$courseId'"))
					{
						$return['message'] = "QUERY TO SERVER FAILED: " . $mysqli->error;
					}elseif(!empty($tempArray)){
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
								}else{
									$return['message'] = "QUERY TO SERVER FAILED: " . $mysqli->error;
								}
							}
						}
					}
				}
			}else{
				$return['message'] = "QUERY TO SERVER FAILED: " . $mysqli->error;
			}
		}
		$result->free();
		$mysqli->close();
		return json_encode($return);
	}
}

$AjaxRemPref = new AjaxRemPref();
echo $AjaxRemPref->remPref();
?>