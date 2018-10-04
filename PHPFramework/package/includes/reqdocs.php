<?php
require('config/config.php');
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}
spl_autoload_register(function ($class) {
    include 'includes/classes/' . $class . '.class.php';
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