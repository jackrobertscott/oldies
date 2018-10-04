<?php
session_start();
$TITLE = "Login";
require('includes/reqdocs.php');

if(!empty($_SESSION['UserId']))
{
    header("Location: index.php");
    exit();
}

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

include("theme/head.php");
?>

<?php if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors)): ?>
<div class="content">
	<p>Your new password has been sent to <?php echo $user->get('Email'); ?>.</p>
</div>
<?php else: ?>
<div class="content">
	<form name="form" class="css-form" action="reset.php" method="POST" novalidate>
		<label>Email Address
			<input type="email" placeholder="Email" ng-model="email" name="email" <?php if(!empty($_POST['email'])){echo 'ng-init="email=\'' . $_POST['email'] . '\'"';}?> required/>
		</label><br/>
		<span ng-show="form.email.$invalid">Please insert a valid email address.<br></span>
		<button ng-disabled="form.$invalid">Reset</button>
	</form>
</div>
<?php endif; ?>

<?php
include("theme/foot.php");
?>