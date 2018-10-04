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

<form action="password.php" method="POST">
	<input type="password" placeholder="Current Password" name="oldpassword" <?php if(!empty($_POST['password'])){echo 'value="'.$_POST['password'].'"';}?>>
	<input type="password" placeholder="New Password" name="newpassword1" <?php if(!empty($_POST['password2'])){echo 'value="'.$_POST['password2'].'"';}?>>
	<input type="password" placeholder="Repeat New Password" name="newpassword2" <?php if(!empty($_POST['password3'])){echo 'value="'.$_POST['password3'].'"';}?>>
	<input type="submit" value="submit">
</form>

<?php
include("includes/footer.php");
?>