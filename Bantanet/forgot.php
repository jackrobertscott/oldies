<?php
session_start();
$TITLE = "Password Reset";
require('includes/reqdocs.php');

if(!empty($_SESSION['Id']))
{
    header("Location: timetable.php");
    exit();
}

if($_SERVER[ 'REQUEST_METHOD' ] == 'POST'){
	if(empty($_POST['email']))
	{
		$errors[] = 'The Email input area is empty.';
	}else{
		$email = $mysqli->real_escape_string(trim($_POST['email']));
		$email = strip_tags($email);
		$pattern = '/\b[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}\b/';
		if(!preg_match($pattern, $email))
		{
			$errors[] = 'The Email is in the incorrect format';
			$email = NULL;
		}
	}
	if(empty($errors))
	{
		$np = SHA1(microtime() . rand());
		$np = substr($np, 0, 6);
		if(!$user->resetPass($email, $np))
		{
			$errors = $user->getErrors();
		}else{
			$em = new EmailMonkey();
			$em->passEmail($email, $np);
			$errors = $em->getErrors();
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
		<?php elseif($_SERVER[ 'REQUEST_METHOD' ] == 'POST'): ?>
			<h3>Email Sent</h3>
			<p>An email with your new password has been sent to <?php echo $email; ?>.</p>
		<?php else: ?>
			<p>Enter your student email adress to reset your password.
			<br><br>
			An email with your new password will be sent to that adress.</p>
		<?php endif; ?>
	</div>
</div>
<div class="text-right">
	<?php if($_SERVER[ 'REQUEST_METHOD' ] == 'POST' && empty($errors)): ?>
		<div class="pretty-pic">
			<h2>:)</h2>
		</div>
	<?php else: ?>
		<form action="forgot.php" method="POST">
			<input type="text" placeholder="Email" name="email" <?php if(!empty($_POST['email'])){echo 'value="' . $_POST['email'] . '"';}?>>
			<input type="submit" class="text-submit" value="Reset">
		</form>
	<?php endif; ?>
</div>
<?php
include("includes/footer.php");
?>