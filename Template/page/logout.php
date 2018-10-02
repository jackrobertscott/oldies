<?php
session_start();
$TITLE = "Logout";
require('../theme/config.php');
require(ASSETS.'open.php');

if($user->logOut())
	$logout = true;

include("../theme/header.php");
?>

<div class="content m-a oh pf-14 mt-14">
	<p>You have logged out.</p>
</div>

<?php
include("../theme/footer.php");
?>