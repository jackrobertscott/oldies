<?php
session_start();
require($_SESSION['DB_CONNECT']);
global $mysqli;
$table_users = $_SESSION['TABLE_USERS'];
$table_friends = $_SESSION['TABLE_FRIENDS'];
$table_images = $_SESSION['TABLE_IMAGES'];
$table_dis = $_SESSION['TABLE_DIS'];
$id = $_SESSION['UserId'];
$filter = $_POST['filter'];
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
				UNION
				SELECT *, 2 as Level
				FROM $table_users
				WHERE Id in 
					(SELECT F1.SecondaryId
					FROM
						(SELECT *
						FROM $table_friends
						WHERE Active = 1) as F1
					WHERE F1.PrimaryId = $id
					AND F1.SecondaryId not in 
						(SELECT F2.PrimaryId
						FROM 
							(SELECT *
							FROM $table_friends
							WHERE Active = 1) as F2
						WHERE F2.SecondaryId = F1.PrimaryId)
					)
				UNION
				SELECT *, 1 as Level
				FROM $table_users
				WHERE Id in 
					(SELECT PrimaryId
					FROM
						(SELECT *
						FROM $table_friends
						WHERE Active = 1) as F1
					WHERE F1.SecondaryId = $id
					AND F1.PrimaryId not in 
						(SELECT F2.SecondaryId
						FROM 
							(SELECT *
							FROM $table_friends
							WHERE Active = 1) as F2
						WHERE F2.PrimaryId = F1.SecondaryId)
					)
				UNION
				SELECT *, 0 as Level
				FROM $table_users
				WHERE Id not in 
					(SELECT F1.PrimaryId
					FROM
						(SELECT *
						FROM $table_friends
						WHERE Active = 1) as F1
					WHERE F1.SecondaryId = $id)
				AND Id not in 
					(SELECT F2.SecondaryId
					FROM
						(SELECT *
						FROM $table_friends
						WHERE Active = 1) as F2
					WHERE F2.PrimaryId = $id)
				) as G1
			/*
			LEFT OUTER JOIN 
				(SELECT *
				FROM $table_dis as dis
				INNER JOIN $table_images as img
				ON dis.PrimaryId = img.Id
				WHERE dis.Active = 1
				AND img.Active = 1) as DIS
			ON DIS.SecondaryId = G1.Id
			*/
			WHERE G1.Id <> $id
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
