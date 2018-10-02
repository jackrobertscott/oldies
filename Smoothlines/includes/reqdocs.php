<?php
require('config/config.php');
require('../../sldb.php');
spl_autoload_register(function ($class) {
    include '../package/includes/classes/' . $class . '.class.php';
});
include('includes/libs/PHPMailer/PHPMailerAutoload.php');
if(!empty($_COOKIE['UserId']) && empty($_SESSION['UserId']))
{
	$cookVer = $_COOKIE['UserId'];
	if($cookId = $mysqli->query("SELECT Id FROM Users WHERE VerCode = '$cookVer'"))
	{
		$cookAssoc = $cookId->fetch_assoc();
		$_SESSION['UserId'] = $cookAssoc['Id'];
		$cookId->free();
	}
}
if(!empty($_SESSION['UserId']))
	$myId = $_SESSION['UserId'];
$user = new User();
?>