<?php
session_start();
$TITLE = "Signup";
require('../theme/config.php');
require(ASSETS.'open.php');

if(!empty($_SESSION['UserId']))
{
    header("Location: ".LINK."index.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	Base::sanPOST();
	$inpArray = array("password1", "password2", "email", "name");
	foreach($inpArray as $value){
		if(empty($_POST[$value]))
			$errors[] = 'The '.$value.' input is empty.';
	}
	if($_POST['password1'] != $_POST['password2'])
		$errors[] = 'Your passwords do not match.';
	$pattern = '/\b[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}\b/';
	if(!preg_match($pattern, $_POST['email']))
		$errors[] = 'Email address is in the incorrect format';
	if(empty($errors))
	{
		$args = array(
		"Name" => $_POST['name']
		);
		$user->create($_POST['email'], $_POST['password1'], $args);
		$errors = $user->getErrors();
	}
}

include("../theme/header.php");
?>

<?php if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors)): ?>
	<p class="pf-14">Account Created.</p>
<?php else: ?>
	<form name="form" class="css-form" action="signup.php" method="POST" novalidate>
		<label class="w-100-28 oh pf-14 fs-13">
			Email Address
			<input class="fr pf-7" type="email" placeholder="Email" ng-model="email" name="email" <?php if(!empty($_POST['email'])){echo 'ng-init="email=\''.$_POST['email'].'\'"';}?> required/>
		</label>
		<span class="w-100 fr ta-r mb-7 fs-10" ng-show="form.email.$invalid">Please insert a valid email address.</span>
		<label class="w-100-28 oh pf-14 fs-13">
			Password
			<input class="fr pf-7" type="password" placeholder="Password" ng-model="password1" name="password1" ng-minlength="<?php echo PASSWORD_MIN; ?>" <?php if(!empty($_POST['password1'])){echo 'ng-init="password1=\''.$_POST['password1'].'\'"';}?> required/>
		</label>
		<span class="w-100 fr ta-r mb-7 fs-10" ng-show="form.password1.$invalid">Please insert a password (Min <?php echo PASSWORD_MIN; ?> Characters).</span>
		<label class="w-100-28 oh pf-14 fs-13">
			Repeat Password
			<input class="fr pf-7" type="password" placeholder="Password" ng-model="password2" name="password2" ng-minlength="<?php echo PASSWORD_MIN; ?>" <?php if(!empty($_POST['password2'])){echo 'ng-init="password2=\''.$_POST['password2'].'\'"';}?> required/>
		</label>
		<span class="w-100 fr ta-r mb-7 fs-10" ng-show="form.password2.$invalid">Please insert a password (Min <?php echo PASSWORD_MIN; ?> Characters).</span>
		<label class="w-100-28 oh pf-14 fs-13">
			Full Name
			<input class="fr pf-7" type="text" placeholder="Name" ng-model="name" name="name" <?php if(!empty($_POST['name'])){echo 'ng-init="name=\''.$_POST['name'].'\'"';}?> required/>
		</label>
		<p class="fs-10 d-i plr-14 w-100-28">on clicking submit, you agree to <?php echo COMPANYNAME; ?>'s <a class="fs-10 d-i" href="terms-and-conditions.php">Terms and Conditions</a> and <a class="fs-10 d-i" href="privacy-policy.php">Privacy Policy</a>.</p>
		<button class="pf-7 mlr-14 mb-14" ng-disabled="form.$invalid">Submit</button>
	</form>
<?php endif; ?>

<?php
include("../theme/footer.php");
?>