<?php
session_start();
$TITLE = "Unsubscribe";
require('includes/reqdocs.php');

if($_SERVER[ 'REQUEST_METHOD' ] == 'POST')
{
	Base::sanPOST();
	$inpArray = array("password", "email");
	foreach ($inpArray as $value) {
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
				$user->dbUpdate("Users", $args, $user->get("Id"));
			}
		}
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
		<?php elseif($_SERVER[ 'REQUEST_METHOD' ] == 'POST'): ?>
			<h3>Successful</h3>
			<p>You will no longer recieve email news letters from us.</p>
		<?php else: ?>
			<h3>Sign In to Unsubscrive</h3>
			<p>Add your details in the spaces provided then click "unsubscribe" to no longer recieve news letters from bantanet.</p>
		<?php endif; ?>
	</div>
</div>
<div class="text-right">
	<?php if($_SERVER[ 'REQUEST_METHOD' ] == 'POST' && empty($errors)): ?>
		<div class="pretty-pic">
			<h2>:)</h2>
		</div>
	<?php else: ?>
		<form action="unsubscribe.php" method="POST">
			<input type="text" placeholder="Email" name="email" <?php if(!empty($_POST['email'])){echo 'value="' . $_POST['email'] . '"';}?>>
			<input type="password" placeholder="Password" name="password" <?php if(!empty($_POST['password'])){echo 'value="' . $_POST['password'] . '"';}?>>
			<input type="submit" class="text-submit" value="Unsubscribe">
		</form>
	<?php endif; ?>
</div>
<?php
include("includes/footer.php");
?>