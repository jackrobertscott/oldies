<?php
session_start();
$TITLE = "Logout";
require('includes/reqdocs.php');

if($user->logOut())
	$logout = true;

include("includes/header.php");
?>



<?php
include("includes/footer.php");
?>