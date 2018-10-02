<?php
session_start();
require($_SESSION['DB_CONNECT']);
$table_posts = $_SESSION['TABLE_POSTS'];
$table_friends = $_SESSION['TABLE_FRIENDS'];
$table_users = $_SESSION['TABLE_USERS'];
$table_follows = $_SESSION['TABLE_FOLLOWS'];
$id = $_SESSION['UserId'];
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
					WHERE Active = 1) as P1
				WHERE P1.UserId = $id
				UNION
				#get friends posts
				SELECT *
				FROM (SELECT *
					FROM $table_posts
					WHERE Active = 1) as P2
				#check post privacy
				WHERE P2.Privacy = 0
				#check following post
				AND P2.Id in
					(SELECT PrimaryId
					FROM $table_follows
					WHERE SecondaryId = $id)
				#check if friends
				AND P2.UserId in 
					(SELECT PrimaryId
					FROM
						(SELECT *
						FROM $table_friends
						WHERE Active = 1) as F1
					WHERE F1.SecondaryId = $id
					AND F1.PrimaryId in 
						(SELECT F2.SecondaryId
						FROM 
							(SELECT *
							FROM $table_friends
							WHERE Active = 1) as F2
						WHERE F2.PrimaryId = F1.SecondaryId)
					)
				) as A1
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