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

include("includes/header.php");
?>

<div class="text corner shadow">
	<form action="unsubscribe.php" method="POST">
		<div class="inp-wrap">
			<p>Email Address</p>
			<input type="text" placeholder="Email" name="email" <?php if(!empty($_POST['email'])){echo 'value="'.$_POST['email'].'"';}?>>
		</div>
		<div class="inp-wrap">
			<p>Password</p>
			<input type="password" placeholder="Password" name="password" <?php if(!empty($_POST['password'])){echo 'value="'.$_POST['password'].'"';}?>>
		</div>
		<div class="inp-wrap">
			<input type="submit" value="Unsubscribe">
		</div>
	</form>
</div>

<?php
include("includes/footer.php");
?>