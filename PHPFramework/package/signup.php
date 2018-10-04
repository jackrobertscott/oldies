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

<form action="signup.php" method="POST">
	<input type="text" placeholder="Email" name="email" <?php if(!empty($_POST['email'])){echo 'value="'.$_POST['email'].'"';}?>>
	<input type="password" placeholder="Password" name="password1" <?php if(!empty($_POST['password1'])){echo 'value="'.$_POST['password1'].'"';}?>>
	<input type="password" placeholder="Repeat Password" name="password2" <?php if(!empty($_POST['password2'])){echo 'value="'.$_POST['password2'].'"';}?>>
	<input type="text" placeholder="Name" name="name" <?php if(!empty($_POST['name'])){echo 'value="'.$_POST['name'].'"';}?>>
	<p style="font-size: 10px;padding-bottom: 0;">on clicking submit, you agree to <?php echo COMPANYNAME; ?>'s <a href="terms-and-conditions.php">Terms and Conditions</a> and <a href="privacy-policy.php">Privacy Policy</a>.</p>
	<input type="submit" value="submit">
</form>

<?php
include("includes/footer.php");
?>