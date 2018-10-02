<?php
session_start();
require($_SESSION['DB_CONNECT']);
require($_SESSION['ASSETS'].'classes/Base.class.php');
require($_SESSION['ASSETS'].'classes/Link.class.php');
$one = new Link($_SESSION['TABLE_FRIENDS'], $_POST['Id'], $_SESSION['UserId'], false);
$two = new Link($_SESSION['TABLE_FRIENDS'], $_SESSION['UserId'], $_POST['Id'], false);
if($one->exists())
	$one->deactivate();
if($two->exists())
	$two->deactivate();
return json_encode(array_merge($one->getErrors(), $two->getErrors()));
?>