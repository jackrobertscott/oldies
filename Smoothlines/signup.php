<?php
session_start();
$TITLE = "Signup";
require('includes/reqdocs.php');

if(!empty($_SESSION['UserId']))
{
    header("Location: index.php");
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
		$errors[] = 'The Email is in the incorrect format';
	if(empty($errors))
	{
		$args = array(
		"Name" => $_POST['name']
		);
		$user->create($_POST['email'], $_POST['password1'], $args);
		$errors = $user->getErrors();
		if(empty($errors))
		{
			header("Location: index.php");
    		exit();
		}
	}
}

include("includes/header.php");
?>

<div class="text corner shadow">
	<form action="signup.php" method="POST">
		<div class="inp-wrap">
			<p>Email Address</p>
			<input type="text" placeholder="Email" name="email" <?php if(!empty($_POST['email'])){echo 'value="'.$_POST['email'].'"';}?>>
		</div>
		<div class="inp-wrap">
			<p>Password</p>
			<input type="password" placeholder="Password" name="password1" <?php if(!empty($_POST['password1'])){echo 'value="'.$_POST['password1'].'"';}?>>
		</div>
		<div class="inp-wrap">
			<p>Repeat Password</p>
			<input type="password" placeholder="Password" name="password2" <?php if(!empty($_POST['password2'])){echo 'value="'.$_POST['password2'].'"';}?>>
		</div>
		<div class="inp-wrap">
			<p>Full Name</p>
			<input type="text" placeholder="Name" name="name" <?php if(!empty($_POST['name'])){echo 'value="'.$_POST['name'].'"';}?>>
		</div>
		<div class="inp-wrap">
			<p style="font-size: 10px;line-height: 12px;">on clicking submit, you agree to <?php echo COMPANYNAME; ?>'s <br><a href="terms-and-conditions.php">Terms and Conditions</a> and <a href="privacy-policy.php">Privacy Policy</a>.</p>
			<input type="submit" value="submit">
		</div>
	</form>
</div>

<?php
include("includes/footer.php");
?>