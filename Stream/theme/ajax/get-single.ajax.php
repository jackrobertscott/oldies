<?php
session_start();
require($_SESSION['DB_CONNECT']);
$table = $_SESSION['TABLE_POSTS'];
$follows = $_SESSION['TABLE_FOLLOWS'];
$id = $_SESSION['UserId'];
$postId = $_POST['Id'];
$query =    "SELECT *, (SELECT COUNT(*) FROM $follows WHERE Active = 1 AND PrimaryId = P.Id AND SecondaryId = $id) as Follows
			FROM (SELECT * FROM $table WHERE Active = 1) as P
			WHERE P.Id = $postId";
if($result = $mysqli->query($query))
{
	while($row = $result->fetch_assoc())
	{
		$return[] = $row;
	}
	$result->free();
}
echo json_encode($return[0]);
?>