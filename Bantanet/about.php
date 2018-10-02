<?php
session_start();
$TITLE = "About";
require('includes/reqdocs.php');
include("includes/header.php");
?>
<div class="text-title">
	<div class="title">
		<h1><?php echo $TITLE; ?></h1>
	</div>
</div>
<div class="text-left">
	<div class="desc">
		<p>bantanet, the social timetable</p>
	</div>
</div>
<div class="text-right">
	<h2>What is bantanet?</h2>
	<p>
	bantanet was founded with the intention of linking people together better by sharing their university timetables and calendars. We found it hard to make arrangements with friends for meet ups as well as checking if we were in any of each others classes. As such, there was a clear area of deficiency in social organisation which we decided to jump at by creating bantanet. 
	<br><br>
	bantanet main goal is to make it easier for friends to meet up and organise their lives around each others timetables.
	<br><br>
	If you are unsure about any part of bantanet, have a question, what to suggest an improvement, or just want to say hey, then feel free to contact us about it anytime on the contact page. click <a href="contact.php"><u>here</u></a> to go to the contact page 
	</p>
	<br>
</div>
<?php
include("includes/footer.php");
?>