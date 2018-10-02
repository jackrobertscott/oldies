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

$page_title = "FAQ";
include('includes/header.php');
?>

<?php include('includes/log_in_bar.php'); ?>

<div class="center_it">
	<div class="main_content" >
	
	<?php include ('includes/sidebar.php'); ?>
	
		<div class="main_right">

			<div class="right_art">
				<div class="text">
				
				<h1>Jobsession FAQ</h1>

				<br><h3>What is Jobsession?</h3><br>
				<p>
				Jobsession was built to make it easier for people to find their perfect job. It was built to help anyone find employment that would not 
				only suit their capabilities but else matched their interests. Those reasons were and are our main goals. At Jobsession, we also try and 
				make the process of finding these jobs simpler. Some of the ways in which we do this are by providing a variety of search 
				capabilities for our users and by creating a clear platform on which employers can advertise their employment positions so users 
				can gain more of a personal response towards individual job posts.
				</p>

				<br><h3>What makes Jobsession different?</h3><br>
				<p>
				Jobsession differs from other online job search websites through providing a combination of key characteristics. These characteristics 
				include quality of service and user friendliness. A large part of Jobsession's ethos has been to maintain a bright, clean and 
				enthusiastic design that is easy on our users eyes but also allows individual employers and companies to highlight their own
				characteristics through images and text expression. <br><br>For employers we also offer the rare ability to advertise their jobs for free 
				which opens up our audience to smaller businesses which may not usually be able to afford paying the high advertisement positions on 
				alternate job search websites.
				</p>

				<br><h3>I have forgotten my password.</h3><br>
				<p>
				No worries! Just get us to send you a new password to your email account <a href="upd_password.php">HERE</a>.
				</p>

				<br><h3>How do Employers recieve applications from Users?</h3><br>
				<p>
				When a user applies for a Job, their Quick CV is sent directly to the Employer's email account to be viewed immediatly by that Employer.
				The applicants QuickCV's can also be viewed from the <a href="employer_profile.php">Employer Profile</a> after selecting the specific job.
				</p>

				<br><h3>I uploaded a new image but the old image is still displayed?</h3><br>
				<p>
				Sometimes the browser that you are using can cause the images stored on a website to stay static or unchanged even though you have changed the image.
				To view the new image, just reload your browser window. If this does not work, try restarting your browser. If it still does not work, contact Jobsession for support.
				</p>

				</div><!-- text -->
			</div><!-- right_art -->
		</div><!-- main_right -->
	</div><!-- main_content -->
</div><!-- center_it -->

<?php 
include('includes/footer.php');
?>