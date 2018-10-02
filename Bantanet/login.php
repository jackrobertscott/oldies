<?php
session_start();
$TITLE = "Sign In";
require('includes/reqdocs.php');

if(!empty($_SESSION['Id']))
{
    header("Location: index.php");
    exit();
}

if($_SERVER[ 'REQUEST_METHOD' ] == 'POST'){
	Base::sanPOST();
	$inpArray = array("password", "email");
	foreach ($inpArray as $value) {
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
			header("Location: timetable.php");
    		exit();
		}
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
		<?php else: ?>
			<p>Sign into your account with your student email and password.</p>
		<?php endif; ?>
	</div>
	<ul>
		<a href="signup.php"><li class="highlight"><p>Create Account</p></li></a>
		<a href="forgot.php"><li><p>Forgot Password</p></li></a>
	</ul>
</div>
<div class="text-right">
	<form action="login.php" method="POST">
		<input type="text" placeholder="Email" name="email"<?php if(!empty($_POST['email'])){echo 'value="' . $_POST['email'] . '"';}?>>
		<input type="password" placeholder="Password" name="password" <?php if(!empty($_POST['password'])){echo 'value="' . $_POST['password'] . '"';}?>>
		<input type="submit" class="text-submit" value="submit">
	</form>
</div>
<?php
include("includes/footer.php");
?>