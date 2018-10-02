<?php
session_start();
$TITLE = "Logout";
require('includes/reqdocs.php');

if($user->logOut())
	$Logout = true;

include("includes/header.php");
?>
<div class="text-title">
	<div class="title">
		<h1><?php echo $TITLE; ?></h1>
	</div>
</div>
<div class="text-left">
	<div class="desc">
		<p>You have successfully logged out of your account.</p>
		<p>See you later :)</p>
	</div>
</div>
<div class="text-right">
	<div class="pretty-pic">
		<h2><span class="icon-power"></span></h2>
	</div>
</div>
<?php
include("includes/footer.php");
?>