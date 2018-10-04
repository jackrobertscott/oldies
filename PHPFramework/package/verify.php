<?php
session_start();
$TITLE = "Verify";
require('includes/reqdocs.php');

if(empty($_SESSION['UserId']))
{
    header("Location: login.php");
    exit();
}

if($user->get("Verified"))
{
    header("Location: index.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET['verLinkCode']))
{
	if(empty($_SESSION['UserId']))
		$errors[] = "You must be signed into your account to verify it.";
	$verCode = Base::escape($_GET['verLinkCode']);
	if(empty($verCode))
		$errors[] = 'The Verification Code input area is empty.';
	if(empty($errors))
	{
		if($user->verify($_GET['verLinkCode']))
		{
			header("Location: index.php");
    		exit();
		}
		$errors = $user->getErrors();
	}
}

include("includes/header.php");
?>

<form action="verify.php" method="GET">
	<input type="text" placeholder="Verification Code" name="verLinkCode" <?php if(!empty($_GET['verLinkCode'])){echo 'value="'.$_GET['verLinkCode'].'"';}?>>
	<input type="submit" value="submit">
</form>

<?php
include("includes/footer.php");
?>