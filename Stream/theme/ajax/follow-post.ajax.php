<?php
session_start();
require($_SESSION['DB_CONNECT']);
require($_SESSION['ASSETS'].'classes/Base.class.php');
require($_SESSION['ASSETS'].'classes/Link.class.php');
$link = new Link($_SESSION['TABLE_FOLLOWS'], $_POST['Id'], $_SESSION['UserId'], false);
if($link->exists())
{
	$link->deactivate();
}else{
	$link->create();
}
return json_encode($link->getErrors());
?>