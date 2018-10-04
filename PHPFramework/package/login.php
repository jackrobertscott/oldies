<?php
session_start();
$TITLE = "Login";
require('includes/reqdocs.php');

if(!empty($_SESSION['UserId']))
{
    header("Location: index.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	Base::sanPOST();
	$inpArray = array("email", "password");
	foreach($inpArray as $value){
		if(empty($_POST[$value]))
			$errors[] = 'The '.$value.' input is empty.';
	}
	$pattern = '/\b[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}\b/';
	if(!preg_match($pattern, $_POST['email']))
		$errors[] = 'The Email is in the incorrect format';
	if(empty($errors))
	{
		if(!$user->logIn($_POST['email'], $_POST['password']))
			$errors = $user->getErrors();
	}
}

include("includes/header.php");
?>

<form action="login.php" method="POST">
	<input type="text" placeholder="Email" name="email"<?php if(!empty($_POST['email'])){echo 'value="'.$_POST['email'].'"';}?>>
	<input type="password" placeholder="Password" name="password" <?php if(!empty($_POST['password'])){echo 'value="'.$_POST['password'].'"';}?>>
	<input type="submit" value="submit">
</form>

<?php
include("includes/footer.php");
?>