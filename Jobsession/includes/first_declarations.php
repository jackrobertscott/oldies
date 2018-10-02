<?php
$id = $_SESSION['id'];
//store all errors
$errors = array();
//store all user data
global $email_des;
$email_des['header'] = '<html><body style="background-color: #ffffff;margin: 0;padding: 0;font-family: arial;">
						<div style="margin: 0 auto;padding: 0;width: 560px;">
							<div style="background-color: #3fa8bf;width: 100%;margin: 0;padding: 0;height: 20px;"><p style="padding: 0;margin: 0;color: #3fa8bf;">@</p></div>
							<img src="http://jobsession.com.au/images/logo_small.png" style="border: none;width: 150px;height: 150px;margin: 20px auto;display: block;">
							<h2 style="text-align: center;margin: 0;padding: 0;">Jobsession</h2>
							<h4 style="text-align: center;margin: 0;padding: 10px 0 0;">We support small businesses</h4><br>';
$email_des['footer'] = '<br><p style="font-size: 12px;">Jack Scott<br><br>Founder<br><br><a href="http://jobsession.com.au/" style="text-decoration: none;">jobsession.com.au</a></p>
						<hr><p style="font-size: 10px;">Jobsession. 74 James Street, Northbridge, WA 6003
						<br><br>Don\'t want to receive news letters from Jobsession: <a href="http://jobsession.com.au/unsubscribe.php" style="text-decoration:none;">Unsubscribe</a>.
						<br><br>You\'ve received this service announcement email to update you about important changes within Jobsession.</p>
						</div></body></html>';
?>