<?php
//database connection page
require(DB_CONNECT);
//autoload classes
spl_autoload_register(function ($class) {
    include ASSETS.'classes/'.$class.'.class.php';
});
//include mailing system
include(ASSETS.'libs/PHPMailer/PHPMailerAutoload.php');
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