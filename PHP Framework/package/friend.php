<?php
session_start();
$TITLE = "Friends";
require('includes/reqdocs.php');

if(empty($_SESSION['UserId']))
{
    header("Location: login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	Base::sanPOST();
	$friend = new Friend();
	if(!$friend->create($_POST['requestId']))
		$errors = $friend->getErrors();
}

include("includes/header.php");
?>



<?php
include("includes/footer.php");
?>