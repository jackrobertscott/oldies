<?php
session_start();
$TITLE = "Account";
require('includes/reqdocs.php');

if(empty($_SESSION['Id']))
{
    header("Location: login.php");
    exit();
}

if($_SERVER[ 'REQUEST_METHOD' ] == 'POST'){
	Base::sanPOST();
	$inpArray = array("firstname", "lastname");
	foreach ($inpArray as $value) {
		if(empty($_POST[$value]))
			$errors[] = 'The '.$value.' input is empty.';
	}
	if(empty($errors))
	{
		$args = array(
		"FirstName" => $_POST['firstname'],
		"LastName" => $_POST['lastname']
		);
		$user->dbUpdate("Users", $args, $myId);
		$errors = $user->getErrors();
	}
}

include("includes/header.php");
?>
<div class="text-title">
	<div class="title">
		<h1><?php echo $TITLE; ?></h1>
	</div>
</div>
<div class="text-left">
	<div class="desc">
		<?php if(!empty($errors)): ?>
			<?php include('includes/error-notice.php'); ?>
		<?php elseif($_SERVER[ 'REQUEST_METHOD' ] == 'POST'): ?>
			<h3>Saved</h3>
			<p>Your update was successful.</p>
		<?php else: ?>
			<p>Update your account information.</p>
		<?php endif; ?>
	</div>
	<ul>
		<a href="password.php"><li class="highlight"><p>Change Password</p></li></a>
	</ul>
</div>
<div class="text-right">
	<form action="update.php" method="POST">
		<input type="text" placeholder="First Name" name="firstname" <?php if(!empty($_POST['firstname'])){echo 'value="' . $_POST['firstname'] . '"';}else{echo 'value="' . $user->get("FirstName") . '"';}?>>
		<input type="text" placeholder="Last Name" name="lastname" <?php if(!empty($_POST['lastname'])){echo 'value="' . $_POST['lastname'] . '"';}else{echo 'value="' . $user->get("LastName") . '"';}?>>
		<input type="submit" class="text-submit" value="submit">
	</form>
</div>
<?php
include("includes/footer.php");
?>