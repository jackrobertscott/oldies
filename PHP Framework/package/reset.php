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
	$inpArray = array("email");
	foreach($inpArray as $value){
		if(empty($_POST[$value]))
			$errors[] = 'The '.$value.' input is empty.';
	}
	$pattern = '/\b[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}\b/';
	if(!preg_match($pattern, $_POST['email']))
		$errors[] = 'The Email is in the incorrect format';
	if(empty($errors))
	{
		$np = SHA1(microtime() . rand());
		$np = substr($np, 0, 6);
		if(!$user->resetPass($email, $np))
			$errors = $user->getErrors();
	}
}

include("includes/header.php");
?>

<form action="reset.php" method="POST">
	<input type="text" placeholder="Email" name="email" <?php if(!empty($_POST['email'])){echo 'value="' . $_POST['email'] . '"';}?>>
	<input type="submit" class="text-submit" value="Reset">
</form>

<?php
include("includes/footer.php");
?>