<?php
session_start();
$TITLE = "Contact";
require('includes/reqdocs.php');

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	Base::sanPOST();
	$inpArray = array("subject", "email", "message");
	foreach($inpArray as $value){
		if(empty($_POST[$value]))
			$errors[] = 'The '.$value.' input is empty.';
	}
	$pattern = '/\b[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}\b/';
	if(!preg_match($pattern, $_POST['email']))
		$errors[] = 'The Email is in the incorrect format';
	if(empty($errors))
	{
		$em = new EmailMonkey();
		$em->sendToSupport($_POST['email'], $_POST['subject'], $_POST['message']);
		$errors = $em->getErrors();
	}
}

include("includes/header.php");
?>

<div class="text corner shadow">
	<form action="contact.php" method="POST">
		<div class="inp-wrap">
			<p>Subject</p>
			<input type="text" placeholder="Subject" name="subject" <?php if(!empty($_POST['subject'])){echo 'value="'.$_POST['subject'].'"';} ?>>
		</div>
		<div class="inp-wrap">
			<p>Email Address</p>
			<input type="text" placeholder="Email" name="email" <?php if(!empty($_POST['email'])){echo 'value="'.$_POST['email'].'"';} ?>>
		</div>
		<div class="inp-wrap">
			<p>Message</p>
			<textarea name="message" placeholder="Message"><?php if(!empty($_POST['message'])) echo $_POST['message']; ?></textarea>
		</div>
		<div class="inp-wrap">
			<input type="submit" value="submit">
		</div>
	</form>
</div>

<?php
include("includes/footer.php");
?>