<?php
session_start();
$TITLE = "Password";
require('includes/reqdocs.php');

if(empty($_SESSION['UserId']))
{
    header("Location: login.php");
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

include("theme/head.php");
?>

<?php if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors)): ?>
<div class="content">
	<p>Password Changed.</p>
</div>
<?php else: ?>
<div class="content">
	<form name="form" class="css-form" action="password.php" method="POST" novalidate>
		<label>Current Password
			<input type="password" placeholder="Current Password" ng-model="oldpassword" name="oldpassword" ng-minlength="<?php echo PASSWORD_MIN; ?>" <?php if(!empty($_POST['oldpassword'])){echo 'ng-init="oldpassword=\''.$_POST['oldpassword'].'\'"';}?> required/>
		</label><br/>
		<label>New Password
			<input type="password" placeholder="New Password" ng-model="newpassword1" name="newpassword1" ng-minlength="<?php echo PASSWORD_MIN; ?>" <?php if(!empty($_POST['newpassword1'])){echo 'ng-init="newpassword1=\''.$_POST['newpassword1'].'\'"';}?> required/>
		</label><br/>
		<span ng-show="form.newpassword1.$invalid">Please insert a password (Min <?php echo PASSWORD_MIN; ?> Characters).<br></span>
		<label>Repeat New Password
			<input type="password" placeholder="New Password" ng-model="newpassword2" name="newpassword2" ng-minlength="<?php echo PASSWORD_MIN; ?>" <?php if(!empty($_POST['newpassword2'])){echo 'ng-init="newpassword2=\''.$_POST['newpassword2'].'\'"';}?> required/>
		</label><br/>
		<span ng-show="form.newpassword2.$invalid">Please insert a password (Min <?php echo PASSWORD_MIN; ?> Characters).<br></span>
		<button ng-disabled="form.$invalid">Submit</button>
	</form>
</div>
<?php endif; ?>

<?php
include("theme/foot.php");
?>