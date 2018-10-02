<?php
session_start();
$TITLE = "Contact";
require('../theme/config.php');
require(ASSETS.'open.php');

//IMPLEMENTATION OF RECAPTURE ENCOURAGED

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

include("../theme/header.php");
?>

<div class="content m-a oh pf-14 mt-14">
	<form name="form" class="css-form" action="contact.php" method="POST" novalidate>
		<label class="w-100 fl ptb-7">
			Subject
			<input class="fr pf-7" type="text" placeholder="Subject" ng-model="subject" name="subject" <?php if(!empty($_POST['subject'])){echo 'ng-init="subject=\''.$_POST['subject'].'\'"';} ?> required/>
		</label>
		<label class="w-100 fl ptb-7">
			Email Address
			<input class="fr pf-7" type="email" placeholder="Email" ng-model="email" name="email" <?php if(!empty($_POST['email'])){echo 'ng-init="email=\''.$_POST['email'].'\'"';} ?> required/>
		</label>
		<span class="w-100 fr ta-r mb-7 fs-10" ng-show="form.email.$invalid">Please insert a valid email address.</span>
		<label class="w-100 fl ptb-7">
			Message
			<textarea class="fr pf-7" placeholder="Message" name="message" ng-model="message" <?php if(!empty($_POST['message'])){echo 'ng-init="message=\''.$_POST['message'].'\'"';} ?> required></textarea>
		</label>
		<button class="fr pf-7 mtb-7" ng-disabled="form.$invalid">Submit</button>
	</form>
</div>

<?php
include("../theme/footer.php");
?>