<?php
session_start();
$TITLE = "Verification";
require('includes/reqdocs.php');

if(empty($_SESSION['Id']))
{
    header("Location: login.php");
    exit();
}

if($user->get("Verified"))
{
    header("Location: timetable.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'GET'){
	if(empty($_SESSION['Id']))
		$errors[] = "You must be signed into your account to verify it.";
	$verCode = Base::escape($_GET['verLinkCode']);
	if(empty($verCode))
		$errors[] = 'The Verification Code input area is empty.';
	if(empty($errors))
	{
		//Be mindful below directly connecting the GET variable to the function
		$user->verify($_GET['verLinkCode']);
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
		<?php if(!empty($_GET['verLinkCode']) && !empty($errors)): ?>
			<?php include('includes/error-notice.php'); ?>
		<?php elseif(!empty($_GET['verLinkCode'])): ?>
			<h3>Success</h3>
			<p>Your account has successfully been verified. You can now access all features.</p>
		<?php else: ?>
			<p>Email sent to  <u><?php echo $user->get("Email"); ?></u>.</p>
			<p>Check email has not been made <u>spam</u>.</p>
		<?php endif; ?>
	</div>
</div>
<div class="text-right">
	<?php if(!empty($_GET['verLinkCode']) && empty($errors)): ?>
		<div class="pretty-pic">
			<h2>:)</h2>
		</div>
	<?php else: ?>
		<p>If you cannot find the verification email, check your spam mail.</p>
		<form action="verify.php" method="GET">
			<input type="text" placeholder="Verification Code" name="verLinkCode" <?php if(!empty($_GET['verLinkCode'])){echo 'value="' . $_GET['verLinkCode'] . '"';}?>>
			<input type="submit" class="text-submit" value="submit">
		</form>
	<?php endif; ?>
</div>
<?php
include("includes/footer.php");
?>