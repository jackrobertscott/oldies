<?php
session_start();
$TITLE = "Verify";
require('includes/reqdocs.php');

if(empty($_SESSION['UserId']))
{
    header("Location: login.php");
    exit();
}

if($user->get("Verified"))
{
    header("Location: index.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET['verLinkCode']))
{
	if(empty($_SESSION['UserId']))
		$errors[] = "You must be signed into your account to verify it.";
	$verCode = Base::escape($_GET['verLinkCode']);
	if(empty($verCode))
		$errors[] = 'The Verification Code input area is empty.';
	if(empty($errors))
	{
		if($user->verify($_GET['verLinkCode']))
		{
			header("Location: index.php");
    		exit();
		}
		$errors = $user->getErrors();
	}
}elseif(!empty($_POST['resend'])){
	$em = new EmailMonkey();
	if(!$em->verifyemail($user->get('VerCode'), $user->get('Email')))
	{
		$this->errors = $em->getErrors();
		return false;
	}
}

include("includes/header.php");
?>

<div class="text corner shadow">
	<?php if(!empty($_GET['verLinkCode']) && empty($errors)): ?>
		<p>Your account has been verified.</p>
	<?php else: ?>
		<form action="verify.php" method="GET">
			<div class="inp-wrap">
				<p>Verification Code</p>
				<input type="text" placeholder="Verification Code" name="verLinkCode" <?php if(!empty($_GET['verLinkCode'])){echo 'value="'.$_GET['verLinkCode'].'"';}?>>
			</div>
			<div class="inp-wrap">
				<input type="submit" value="submit">
			</div>
		</form>
		<?php if(!empty($_POST['resend'])): ?>
		<div class="inp-wrap">
			<p>A new email was sent to <?php echo $user->get('Email'); ?></p>
		</div>
		<?php else: ?>
		<form action="verify.php" method="POST">
			<input type="hidden" name="resend" value="true">
			<div class="inp-wrap">
				<input type="submit" value="Resend Email" style="background-color: #2fc468;">
			</div>
		</form>
		<?php endif; ?>
	<?php endif; ?>
</div>
<?php
include("includes/footer.php");
?>