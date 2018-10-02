<?php
session_start();
$TITLE = "Verify";
require('../theme/config.php');
require(ASSETS.'open.php');

if(empty($_SESSION['UserId']))
{
    header("Location: ".LINK."page/login.php");
    exit();
}

if($user->get("Verified"))
{
    header("Location: ".LINK."index.php");
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

include("../theme/header.php");
?>

<?php if(!empty($_GET['verLinkCode']) && empty($errors)): ?>
<div class="content m-a oh pf-14 mt-14">
	<p>Your account has been verified.</p>
</div>
<?php else: ?>
<div class="content m-a oh pf-14 mt-14">
	<form name="form" class="css-form" action="verify.php" method="GET" novalidate>
		<label class="w-100 fl ptb-7">
			Verification Code
			<input class="fr pf-7" type="text" placeholder="Verification Code" ng-model="verLinkCode" name="verLinkCode" <?php if(!empty($_GET['verLinkCode'])){echo 'ng-init="verLinkCode=\''.$_GET['verLinkCode'].'\'"';}?> required/>
		</label>
		<button class="fr pf-7 mtb-7" ng-disabled="form.$invalid">Submit</button>
	</form>
	<?php if(!empty($_POST['resend'])): ?>
	A new email was sent to <?php echo $user->get('Email'); ?>
	<?php else: ?>
	<form name="form" class="css-form" action="verify.php" method="POST" novalidate>
		<input class="fr pf-7" type="hidden" name="resend" value="true"/>
		<button class="fr pf-7 mtb-7" ng-disabled="form.$invalid" style="background-color: #2fc468;">Resend</button>
	</form>
	<?php endif; ?>
</div>
<?php endif; ?>

<?php
include("../theme/footer.php");
?>