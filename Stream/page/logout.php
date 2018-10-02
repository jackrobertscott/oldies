<?php
session_start();
$TITLE = "Logout";
require('../theme/config.php');
require(ASSETS.'open.php');

if($user->logOut())
	$logout = true;

include("../theme/header.php");
?>

<p class="pf-14">You have logged out.</p>

<?php
include("../theme/footer.php");
?>