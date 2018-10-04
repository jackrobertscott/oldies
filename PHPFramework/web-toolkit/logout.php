<?php
session_start();
$TITLE = "Logout";
require('includes/reqdocs.php');

if($user->logOut())
	$logout = true;

include("theme/head.php");
?>

<div class="content">
	<p>You have logged out.</p>
</div>

<?php
include("theme/foot.php");
?>