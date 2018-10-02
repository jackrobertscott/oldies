<?php
session_start();
$TITLE = "Subscribe";
require('../theme/config.php');
require(ASSETS.'open.php');

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	Base::sanPOST();
	$inpArray = array("password", "email");
	foreach($inpArray as $value){
		if(empty($_POST[$value]))
			$errors[] = 'The '.$value.' input is empty.';
	}
	$pattern = '/\b[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}\b/';
	if(!preg_match($pattern, $_POST['email']))
		$errors[] = 'The Email is in the incorrect format';
	if(empty($errors))
	{
		if($user->login($_POST['email'], $_POST['password']))
		{
			$user->subscribe();
		}
		$errors = $user->getErrors();
	}
}

include("../theme/header.php");
?>

<?php if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors)): ?>
	<p class="pf-14">You are subscribed.</p>
<?php else: ?>
	<form name="form" class="css-form" action="subscribe.php" method="POST" novalidate>
		<label class="w-100-28 oh pf-14 fs-13">
			Email Address
			<input class="fr pf-7" type="email" placeholder="Email" ng-model="email" name="email" <?php if(!empty($_POST['email'])){echo 'ng-init="email=\''.$_POST['email'].'\'"';}?> required/>
		</label>
		<span class="w-100 fr ta-r mb-7 fs-10" ng-show="form.email.$invalid">Please insert a valid email address.</span>
		<label class="w-100-28 oh pf-14 fs-13">
			Password
			<input class="fr pf-7" type="password" placeholder="Password" ng-model="password" name="password" ng-minlength="<?php echo PASSWORD_MIN; ?>" <?php if(!empty($_POST['password'])){echo 'ng-init="password=\''.$_POST['password'].'\'"';}?> required/>
		</label>
		<span class="w-100 fr ta-r mb-7 fs-10" ng-show="form.password.$invalid">Please insert a password (Min <?php echo PASSWORD_MIN; ?> Characters).</span>
		<button class="fr pf-7 mlr-14 mb-14" ng-disabled="form.$invalid">subscribe</button>
	</form>
<?php endif; ?>

<?php
include("../theme/footer.php");
?>