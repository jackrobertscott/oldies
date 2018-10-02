<?php
session_start();
$TITLE = "Sign Up";
require('includes/reqdocs.php');

if(!empty($_SESSION['Id']))
{
    header("Location: timetable.php");
    exit();
}

if($_SERVER[ 'REQUEST_METHOD' ] == 'POST')
{
	Base::sanPOST();
	$inpArray = array("password1", "password2", "email", "firstname", "lastname");
	foreach ($inpArray as $value) {
		if(empty($_POST[$value]))
			$errors[] = 'The '.$value.' input is empty.';
	}
	if($_POST['password1'] != $_POST['password2'])
		$errors[] = 'Your passwords do not match.';
	$pattern = '/\b[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}\b/';
	if(!preg_match($pattern, $_POST['email']))
		$errors[] = 'The Email is in the incorrect format';
	foreach($UNIARRAY as $unikey)
	{
		if(!endswith($_POST['email'], $unikey[2]))
		{
			$errors[] = "The email entered must be a registered university email adress with bantanet.";
		}else{
			$uniId = $unikey[0];
			$uni = $unikey[1];
		}
	}
	if(empty($errors))
	{
		$args = array(
		"FirstName" => $_POST['firstname'],
		"LastName" => $_POST['lastname'],
		"UniversityId" => $uniId,
		"University" => $uni
		);
		$user->createUser($email, $password, $args);
		$errors = $user->getErrors();
	}
}

include("includes/header.php");
?>
<div class="text-title">
	<div class="title">
		<h1><?php echo $TITLE; ?></h1>
	</div>
</div>
<div class="text-left">
	<div class="desc">
		<?php if(!empty($errors)): ?>
			<?php include('includes/error-notice.php'); ?>
		<?php elseif(!empty($_SESSION['Id'])): ?>
			<h3>Congratz</h3>
			<p>A <a href="verify.php">verification</a> email was sent to your account.</p>
		<?php else: ?>
			<p>bantanet hopes to make it easier for friends to meet up and organise their lives around each others timetables.</p>
		<?php endif; ?>
	</div>
	<ul>
		<a href="login.php"><li class="highlight"><p>Log In</p></li></a>
	</ul>
</div>
<div class="text-right">
	<?php if(!empty($_SESSION['Id'])): ?>
		<p>Email sent to  <u><?php echo $user->get("Email"); ?></u>.</p>
		<p>Check email has not been made <u>spam</u>.</p>
	<?php else: ?>
		<form action="signup.php" method="POST">
			<input type="text" placeholder="Student Email" name="email" <?php if(!empty($_POST['email'])){echo 'value="' . $_POST['email'] . '"';}?>>
			<input type="password" placeholder="Password" name="password" <?php if(!empty($_POST['password'])){echo 'value="' . $_POST['password'] . '"';}?>>
			<input type="password" placeholder="Repeat Password" name="password2" <?php if(!empty($_POST['password2'])){echo 'value="' . $_POST['password2'] . '"';}?>>
			<input type="text" placeholder="First Name" name="firstname" <?php if(!empty($_POST['firstname'])){echo 'value="' . $_POST['firstname'] . '"';}?>>
			<input type="text" placeholder="Last Name" name="lastname" <?php if(!empty($_POST['lastname'])){echo 'value="' . $_POST['lastname'] . '"';}?>>
			<p style="font-size: 10px;padding-bottom: 0;">on clicking submit, you agree to bantanet's <a href="terms-and-conditions.php">Terms and Conditions</a> and <a href="privacy-policy.php">Privacy Policy</a>.</p>
			<input type="submit" class="text-submit" value="submit">
		</form>
	<?php endif; ?>
</div>
<?php
include("includes/footer.php");
?>