<?php
Class AjaxRemFriend
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
			$query = "DELETE FROM Friends WHERE (Sender = '$friendId' AND Receiver = '$id') OR (Sender = '$id' AND Receiver = '$friendId')";
			if(!$mysqli->query($query))
			{
				$return['message'] = "QUERY TO SERVER FAILED: " . $mysqli->error;
			}
		}
		$mysqli->close();
		return json_encode($return);
	}
}

$Ajaxremfriend = new AjaxRemFriend();
echo $Ajaxremfriend->friendProcess();
?>