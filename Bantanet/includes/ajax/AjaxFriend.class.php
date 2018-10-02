<?php
Class AjaxFriend
{
	function friendProcess()
	{
		session_start();
		require('../../../../db-dets.php');
		$return = array();
		$return['message'] = "";
		$friendId = @$_POST['friendId'];
		$return['receiver'] = $friendId;
		$id = $_SESSION['Id'];
		if(empty($friendId)){$return['message'] .= "FRIEND ID FIELD EMPTY.";}
		if(empty($id)){$return['message'] .= "SESSION ID NOT SET.";}
		if($return['message'] == "")
		{
			$query = "SELECT Id FROM Friends WHERE (Sender = '$friendId' AND Receiver = '$id') OR (Sender = '$id' AND Receiver = '$friendId')";
			if(!$mysqli->query($query))
			{
				$return['message'] = "QUERY TO SERVER FAILED: " . $mysqli->error;
			}else{
				if($mysqli->affected_rows > 0)
				{
					$unit_query = "DELETE FROM Friends WHERE (Sender = '$friendId' AND Receiver = '$id') OR (Sender = '$id' AND Receiver = '$friendId')";
					$return['status'] = "deleted";
				}else{
					$unit_query = "INSERT INTO Friends (Sender, Receiver) VALUES ('$id', '$friendId')";
					$return['status'] = "inserted";
				}
				if(!$mysqli->query($unit_query))
				{
					$return['message'] = "QUERY TO SERVER FAILED: " . $mysqli->error;
				}
			}
		}
		$mysqli->close();
		return json_encode($return);
	}
}

$Ajaxfriend = new AjaxFriend();
echo $Ajaxfriend->friendProcess();
?>