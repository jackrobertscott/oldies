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
		{
			$errors = $user->getErrors();
		}else{
			header("Location: index.php");
    		exit();
		}
	}
}

include("includes/header.php");
?>

<div class="text corner shadow">
	<form action="login.php" method="POST">
		<div class="inp-wrap">
			<p>Email Address</p>
			<input type="text" placeholder="Email" name="email"<?php if(!empty($_POST['email'])){echo 'value="'.$_POST['email'].'"';}?>>
		</div>
		<div class="inp-wrap">
			<p>Password</p>
			<input type="password" placeholder="Password" name="password" <?php if(!empty($_POST['password'])){echo 'value="'.$_POST['password'].'"';}?>>
		</div>
		<div class="inp-wrap">
			<p style="font-size: 10px;padding-top: 18px;"><a href="reset.php">forgot password</a></p>
			<input type="submit" value="submit">
		</div>
	</form>
</div>

<?php
include("includes/footer.php");
?>