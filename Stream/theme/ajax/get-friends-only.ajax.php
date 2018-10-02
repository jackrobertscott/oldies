<?php
session_start();
require($_SESSION['DB_CONNECT']);
global $mysqli;
$table_users = $_SESSION['TABLE_USERS'];
$table_friends = $_SESSION['TABLE_FRIENDS'];
$id = $_SESSION['UserId'];
$filter = $_GET['filter'];
$query =   "SELECT *
			FROM
				(SELECT *, 3 as Level
				FROM $table_users
				WHERE Id in 
					(SELECT F1.PrimaryId
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
				) as G1
			WHERE Id <> $id
			ORDER BY G1.Level DESC";
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
