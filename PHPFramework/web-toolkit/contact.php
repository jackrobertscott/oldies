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
		$em = new EmailManager();
		$em->sendToSupport($_POST['email'], $_POST['subject'], $_POST['message']);
		$errors = $em->getErrors();
	}
}

include("theme/head.php");
?>

<div class="content">
	<form name="form" class="css-form" action="contact.php" method="POST" novalidate>
		<label>Subject
			<input type="text" placeholder="Subject" ng-model="subject" name="subject" <?php if(!empty($_POST['subject'])){echo 'ng-init="subject=\''.$_POST['subject'].'\'"';} ?> required/>
		</label><br/>
		<label>Email Address
			<input type="email" placeholder="Email" ng-model="email" name="email" <?php if(!empty($_POST['email'])){echo 'ng-init="email=\''.$_POST['email'].'\'"';} ?> required/>
		</label><br/>
		<span ng-show="form.email.$invalid">Please insert a valid email address.<br></span>
		<label>Message
			<textarea placeholder="Message" name="message" ng-model="message" <?php if(!empty($_POST['message'])){echo 'ng-init="message=\''.$_POST['message'].'\'"';} ?> required></textarea>
		</label><br/>
		<button ng-disabled="form.$invalid">Submit</button>
	</form>
</div>

<?php
include("theme/foot.php");
?>