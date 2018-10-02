<?php
require('../../db-dets.php');
date_default_timezone_set('Australia/Perth');
require('includes/uniArray.php');
spl_autoload_register(function ($class) {
    include 'includes/classes/' . $class . '.class.php';
});
require('includes/functions.php');
require('includes/PHPMailer/PHPMailerAutoload.php');
if(!empty($_COOKIE['Id']) && empty($_SESSION['Id']))
{
	$cookVer = $_COOKIE['Id'];
	if($cookId = $mysqli->query("SELECT Id FROM Users WHERE VerCode = '$cookVer'"))
	{
		$cookAssoc = $cookId->fetch_assoc();
		$_SESSION['Id'] = $cookAssoc['Id'];
		$cookId->free();
	}
}
$user = new User();
if(!empty($_SESSION['Id']))
	$myId = $_SESSION['Id'];
?>