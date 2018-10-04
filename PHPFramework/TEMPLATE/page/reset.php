<?php
session_start();
$TITLE = "Login";
require('../theme/config.php');
require(ASSETS.'open.php');

if(!empty($_SESSION['UserId']))
{
    header("Location: ".LINK."index.php");
    exit();
}

//IMPLEMENTATION OF RECAPTURE ENCOURAGED

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

include("../theme/header.php");
?>

<?php if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors)): ?>
<div class="content m-a oh pf-14 mt-14">
	<p>Your new password has been sent to <?php echo $user->get('Email'); ?>.</p>
</div>
<?php else: ?>
<div class="content m-a oh pf-14 mt-14">
	<form name="form" class="css-form" action="reset.php" method="POST" novalidate>
		<label class="w-100 fl ptb-7">
			Email Address
			<input class="fr pf-7" type="email" placeholder="Email" ng-model="email" name="email" <?php if(!empty($_POST['email'])){echo 'ng-init="email=\'' . $_POST['email'] . '\'"';}?> required/>
		</label>
		<span class="w-100 fr ta-r mb-7 fs-10" ng-show="form.email.$invalid">Please insert a valid email address.</span>
		<button class="fr pf-7 mtb-7" ng-disabled="form.$invalid">Reset</button>
	</form>
</div>
<?php endif; ?>

<?php
include("../theme/footer.php");
?>