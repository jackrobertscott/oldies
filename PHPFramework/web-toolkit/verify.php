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

if(!empty($_GET['verLinkCode']))
{
	if(empty($_SESSION['UserId']))
		$errors[] = "You must be signed into your account to verify it.";
	$verCode = Base::escape($_GET['verLinkCode']);
	if(empty($verCode))
		$errors[] = 'The Verification Code input area is empty.';
	if(empty($errors))
	{
		if(!$user->verify($_GET['verLinkCode']))
			$errors = $user->getErrors();
	}
}elseif(!empty($_POST['resend'])){
	$em = new EmailManager();
	if(!$em->verifyemail($user->get('VerCode'), $user->get('Email')))
	{
		$this->errors = $em->getErrors();
		return false;
	}
}

include("theme/head.php");
?>

<?php if(!empty($_GET['verLinkCode']) && empty($errors)): ?>
<div class="content">
	<p>Your account has been verified.</p>
</div>
<?php else: ?>
<div class="content">
	<form name="form" class="css-form" action="verify.php" method="GET" novalidate>
		<label>Verification Code
			<input type="text" placeholder="Verification Code" ng-model="verLinkCode" name="verLinkCode" <?php if(!empty($_GET['verLinkCode'])){echo 'ng-init="verLinkCode=\''.$_GET['verLinkCode'].'\'"';}?> required/>
		</label><br/>
		<button ng-disabled="form.$invalid">Submit</button>
	</form>
	<?php if(!empty($_POST['resend'])): ?>
	A new email was sent to <?php echo $user->get('Email'); ?>
	<?php else: ?>
	<form name="form" class="css-form" action="verify.php" method="POST" novalidate>
		<input type="hidden" name="resend" value="true"/>
		<button ng-disabled="form.$invalid" style="background-color: #2fc468;">Resend</button>
	</form>
	<?php endif; ?>
</div>
<?php endif; ?>

<?php
include("theme/foot.php");
?>