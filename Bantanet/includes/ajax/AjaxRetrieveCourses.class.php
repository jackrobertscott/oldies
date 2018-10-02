<?php
Class AjaxRetrieveCourses
{
	function getCourses()
	{
		session_start();
		require('../../../../db-dets.php');
		$return = array();
		$return['message'] = "";
		$return['optArray'] = "";
		$cs = @$_POST['courseSearch'];
		$return['searchPhrase'] = $cs;
		if(empty($cs)){$return['message'] .= "SEARCH PHRASE EMPTY.";}
		if($return['message'] == "")
		{
			$query = "SELECT CourseCode, CourseName, Id FROM Courses WHERE (CourseCode LIKE '%$cs%') OR (CourseName LIKE '%$cs%')";
			$return['searchQuery'] = $query;
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
		$mysqli->close();
		return json_encode($return);
	}
}

$AjaxRetrieveCourses = new AjaxRetrieveCourses();
echo $AjaxRetrieveCourses->getCourses();
?>