<?php
session_start();
$TITLE = "Unsubscribe";
require('includes/reqdocs.php');

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
		if($user->logIn($_POST['email'], $_POST['password']))
		{
			if($user->get('Unsubscribed') == 1)
			{
				$errors[] = "This account is already unsubscribed.";
			}else{
				$args = array("Unsubscribed" => 1);
				$user->dbUpdate($args);
			}
		}
		$errors = $user->getErrors();
	}
}

include("theme/head.php");
?>

<?php if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors)): ?>
<div class="content">
	<p>You are unsubscribed.</p>
</div>
<?php else: ?>
<div class="content">
	<form name="form" class="css-form" action="unsubscribe.php" method="POST" novalidate>
		<label>Email Address
			<input type="email" placeholder="Email" ng-model="email" name="email" <?php if(!empty($_POST['email'])){echo 'ng-init="email=\''.$_POST['email'].'\'"';}?> required/>
		</label><br/>
		<span ng-show="form.email.$invalid">Please insert a valid email address.<br></span>
		<label>Password
			<input type="password" placeholder="Password" ng-model="password" name="password" ng-minlength="<?php echo PASSWORD_MIN; ?>" <?php if(!empty($_POST['password'])){echo 'ng-init="password=\''.$_POST['password'].'\'"';}?> required/>
		</label><br/>
		<span ng-show="form.password.$invalid">Please insert a password (Min <?php echo PASSWORD_MIN; ?> Characters).<br></span>
		<button ng-disabled="form.$invalid">Unsubscribe</button>
	</form>
</div>
<?php endif; ?>

<?php
include("theme/foot.php");
?>