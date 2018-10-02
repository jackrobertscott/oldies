<?php
session_start();
$TITLE = "Password";
require('../theme/config.php');
require(ASSETS.'open.php');

if(empty($_SESSION['UserId']))
{
    header("Location: ".LINK."page/login.php");
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

include("../theme/header.php");
?>

<?php if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors)): ?>
	<p class="pf-14">Password Changed.</p>
<?php else: ?>
	<form name="form" class="css-form" action="password.php" method="POST" novalidate>
		<label class="w-100-28 oh pf-14 fs-13">
			Current Password
			<input class="fr pf-7" type="password" placeholder="Current Password" ng-model="oldpassword" name="oldpassword" ng-minlength="<?php echo PASSWORD_MIN; ?>" <?php if(!empty($_POST['oldpassword'])){echo 'ng-init="oldpassword=\''.$_POST['oldpassword'].'\'"';}?> required/>
		</label>
		<label class="w-100-28 oh pf-14 fs-13">
			New Password
			<input class="fr pf-7" type="password" placeholder="New Password" ng-model="newpassword1" name="newpassword1" ng-minlength="<?php echo PASSWORD_MIN; ?>" <?php if(!empty($_POST['newpassword1'])){echo 'ng-init="newpassword1=\''.$_POST['newpassword1'].'\'"';}?> required/>
		</label>
		<span class="w-100 fr ta-r mb-7 fs-10" ng-show="form.newpassword1.$invalid">Please insert a password (Min <?php echo PASSWORD_MIN; ?> Characters).</span>
		<label class="w-100-28 oh pf-14 fs-13">
			Repeat New Password
			<input class="fr pf-7" type="password" placeholder="New Password" ng-model="newpassword2" name="newpassword2" ng-minlength="<?php echo PASSWORD_MIN; ?>" <?php if(!empty($_POST['newpassword2'])){echo 'ng-init="newpassword2=\''.$_POST['newpassword2'].'\'"';}?> required/>
		</label>
		<span class="w-100 fr ta-r mb-7 fs-10" ng-show="form.newpassword2.$invalid">Please insert a password (Min <?php echo PASSWORD_MIN; ?> Characters).</span>
		<button class="fr pf-7 mlr-14 mb-14" ng-disabled="form.$invalid">Submit</button>
	</form>
<?php endif; ?>

<?php
include("../theme/footer.php");
?>