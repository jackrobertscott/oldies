<?php
Class AjaxAddFriend
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
			$query = "UPDATE Friends SET Active = 1 WHERE (Sender = '$friendId' AND Receiver = '$id') OR (Sender = '$id' AND Receiver = '$friendId')";
			if(!$mysqli->query($query))
			{
				$return['message'] = "QUERY TO SERVER FAILED: " . $mysqli->error;
			}
		}
		$mysqli->close();
		return json_encode($return);
	}
}

$Ajaxaddfriend = new AjaxAddFriend();
echo $Ajaxaddfriend->friendProcess();
?>