<?php
session_start();
$TITLE = "Password";
require('includes/reqdocs.php');

if(empty($_SESSION['Id']))
{
    header("Location: login.php");
    exit();
}

if($_SERVER[ 'REQUEST_METHOD' ] == 'POST'){
	if(empty($_POST['password3']) || empty($_POST['password2']))
	{
		$errors[] = 'The new password input area(s) are empty.';
	}elseif($_POST['password3'] != $_POST['password2']){
		$errors[] = 'Your new passwords do not match.';
	}else{
		$newpassword = $mysqli->real_escape_string(trim($_POST['password2']));
		$newpassword = strip_tags($newpassword);
	}
	if(empty($_POST['password']))
	{
		$errors[] = 'The new password input area(s) are empty.';
	}else{
		$password = $mysqli->real_escape_string(trim($_POST['password']));
		$password = strip_tags($password);
	}
	if(empty($errors))
	{
		$user->changePass($password, $newpassword);
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
			<p>Change your password.</p>
		<?php endif; ?>
	</div>
</div>
<div class="text-right">
	<form action="password.php" method="POST">
		<input type="password" placeholder="Current Password" name="password" <?php if(!empty($_POST['password'])){echo 'value="' . $_POST['password'] . '"';}?>>
		<input type="password" placeholder="New Password" name="password2" <?php if(!empty($_POST['password2'])){echo 'value="' . $_POST['password2'] . '"';}?>>
		<input type="password" placeholder="Repeat Password" name="password3" <?php if(!empty($_POST['password3'])){echo 'value="' . $_POST['password3'] . '"';}?>>
		<input type="submit" class="text-submit" value="submit">
	</form>
</div>
<?php
include("includes/footer.php");
?>