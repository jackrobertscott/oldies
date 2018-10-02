<?php
session_start();
require('../../connect_db.php');
require('includes/first_declarations.php');
require('includes/register_functions.php');
require('includes/job_sub_functions.php');
require('includes/PHPMailer/PHPMailerAutoload.php');
include('includes/grab_all_data.php');
$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$_SESSION['url1'] = $url;

$page_title = "Employer";
include ('includes/header.php');
?>
		
<?php include('includes/log_in_bar.php'); ?>

<div class="center_it">
	<div class="main_content" >
	
	<?php include ('includes/sidebar.php'); ?>
	
		<div class="main_right">
		
		<div class="right_art">
			<div class="text">
				<p>You are now a registered employer, welcome to the team!</p>
				<p>You can now create Free Jobs. Just visit the "Post a Job for FREE" option on the side bar.</p>
				<p>Or you can click <a href="job_submit_ini.php">HERE</a> to Post a Job for FREE.</p>
				<p>To edit any of your jobs, just vist your <a href="employer_profile.php">Employer Profile</a></p>
			</div><!-- text -->
		</div><!-- right_art -->
		
		</div><!-- main_right -->
	</div><!-- main_content -->
</div><!-- center_it -->

<?php
include("includes/footer.php");
?>