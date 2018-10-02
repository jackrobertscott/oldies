<?php
session_start();
require($_SESSION['DB_CONNECT']);
$table_posts = $_SESSION['TABLE_POSTS'];
$table_friends = $_SESSION['TABLE_FRIENDS'];
$table_users = $_SESSION['TABLE_USERS'];
$table_follows = $_SESSION['TABLE_FOLLOWS'];
$id = $_SESSION['UserId'];
$friendId = $_POST['Id'];
$query = 	"SELECT *
				, (SELECT COUNT(*) 
				FROM $table_follows 
				WHERE Active = 1 
				AND PrimaryId = A1.Id 
				AND SecondaryId = $id) as Follows
				, (SELECT Name
				FROM $table_users
				WHERE Id = A1.UserId) as Name
			FROM 
				#get this users posts
				(SELECT *
				FROM 
					(SELECT *
					FROM $table_posts
					WHERE Active = 1
					AND Privacy = 0) as P1
				WHERE P1.UserId = $friendId) as A1
			WHERE 
				#check if the post's user accounts are active
				(SELECT Active
				FROM $table_users
				WHERE Id = A1.UserId) = 1 
			ORDER BY A1.Start";
if($result = $mysqli->query($query))
{
	while($row = $result->fetch_assoc())
	{
		$return[] = $row;
	}
	$result->free();
}
echo json_encode($return);
?>