<?php
session_start();
$TITLE = "Logout";
require('includes/reqdocs.php');

if($user->logOut())
	$logout = true;

include("includes/header.php");
?>

<div class="text corner shadow">
	<p>You have logged out.</p>
</div>

<?php
include("includes/footer.php");
?>