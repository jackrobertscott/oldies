<?php
session_start();
$TITLE = "Password";
require('includes/reqdocs.php');

if(empty($_SESSION['UserId']))
{
    header("Location: login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	Base::sanPOST();
	$inpArray = array("oldpassword", "newpassword1", "newpassword2");
	foreach($inpArray as $value){
		if(empty($_POST[$value]))
			$errors[] = 'The '.$value.' input is empty.';
	}
	if($_POST['newpassword1'] != $_POST['newpassword2'])
		$errors[] = 'Your new passwords do not match.';
	if(empty($errors))
	{
		$user->changePass($_POST['oldpassword'], $_POST['newpassword1']);
		$errors = $user->getErrors();
	}
}

include("includes/header.php");
?>

<div class="text corner shadow">
	<form action="password.php" method="POST">
		<div class="inp-wrap">
			<p>Current Password</p>
			<input type="password" placeholder="Current Password" name="oldpassword" <?php if(!empty($_POST['password'])){echo 'value="'.$_POST['password'].'"';}?>>
		</div>
		<div class="inp-wrap">
			<p>New Password</p>
			<input type="password" placeholder="New Password" name="newpassword1" <?php if(!empty($_POST['password2'])){echo 'value="'.$_POST['password2'].'"';}?>>
		</div>
		<div class="inp-wrap">
			<p>Repeat New Password</p>
			<input type="password" placeholder="New Password" name="newpassword2" <?php if(!empty($_POST['password3'])){echo 'value="'.$_POST['password3'].'"';}?>>
		</div>
		<div class="inp-wrap">
			<input type="submit" value="submit">
		</div>
	</form>
</div>

<?php
include("includes/footer.php");
?>