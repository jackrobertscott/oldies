<?php
session_start();
$TITLE = "Contact";
require('includes/reqdocs.php');

if($_SERVER[ 'REQUEST_METHOD' ] == 'POST')
{
	Base::sanPOST();
	$inpArray = array("subject", "email", "message");
	foreach ($inpArray as $value) {
		if(empty($_POST[$value]))
			$errors[] = 'The '.$value.' input is empty.';
	}
	$pattern = '/\b[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}\b/';
	if(!preg_match($pattern, $_POST['email']))
		$errors[] = 'The Email is in the incorrect format';
	require_once('includes/recaptchalib.php');
	$privatekey = "6LfFH_cSAAAAAFpt-f9FyYLq8_OJui3SCQpcX46S";
	$resp = recaptcha_check_answer ($privatekey,
	                            $_SERVER["REMOTE_ADDR"],
	                            $_POST["recaptcha_challenge_field"],
	                            $_POST["recaptcha_response_field"]);
	if (!$resp->is_valid) {
		$errors[] = "Your verification code was incorrectly submited. Please try again.";
	}
	if(empty($errors))
	{
		$em = new EmailMonkey();
		$em->sendToSupport($_POST['email'], $_POST['subject'], $_POST['message']);
		$errors = $em->getErrors();
	}
}

include("includes/header.php");
?>
<script type="text/javascript">
 var RecaptchaOptions = {
    theme : 'white'
 };
 </script>
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
			<h3>Sent</h3>
			<p>Your message was successfully sent to the bantanet team and we will try to reply as promptly as possible.</p>
		<?php else: ?>
			<p>Got a question, have a suggestion for improvement or need some support from the bantanet team? no worries, just sent us a message.</p>
		<?php endif; ?>
	</div>
</div>
<div class="text-right">
	<form action="contact.php" method="POST">
		<input type="text" placeholder="Subject" name="subject" <?php if(!empty($_POST['subject'])){echo 'value="' . $_POST['subject'] . '"';} ?>>
		<input type="text" placeholder="Email Address" name="email" <?php if(!empty($_POST['email'])){echo 'value="' . $_POST['email'] . '"';} ?>>
		<textarea name="message" placeholder="Message"><?php if(!empty($_POST['message'])) echo $_POST['message']; ?></textarea>
		<div style="margin: 14px 0 0 20px;">
			<?php 
			require_once('includes/recaptchalib.php');
			$publickey = "6LfFH_cSAAAAANPRfjvnfoSNHaKeKTdPAFDa_BFl";
			echo recaptcha_get_html($publickey);
			?>
		</div>
		<input type="submit" class="text-submit" value="submit">
	</form>
</div>
<?php
include("includes/footer.php");
?>