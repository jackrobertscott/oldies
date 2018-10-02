	<div class="center_it">	
		<div class="footer_bar" <?php if(!empty($no_search)){ echo 'style="border:none;"'; } ?>>
			<div class="split">
				<h1>Main</h1>
				<p><a href="index.php">Home</a></p>
				<?php if($_SESSION['log']) : ?>
				<p><a href="log_out.php">Log Out</a></p>
				<?php endif ; ?>
				<?php if(!$_SESSION['log']) : ?>
				<p><a href="log_in.php">Log In</a></p>
				<p><a href="emp_user_reg.php?emp_yes=true">Post a Job for FREE</a></p>
				<?php elseif($_SESSION['ver'] != 2) : ?>
				<p><a href="verify_account.php?emp_yes=true">Post a Job for FREE</a></p>
				<?php elseif($_SESSION['es'] != 2) : ?>
				<p><a href="employer_reg.php?emp_yes=true">Post a Job for FREE</a></p>
				<?php else : ?>
				<p><a href="job_submit_ini.php">Post a Job for FREE</a></p>
				<?php endif; ?>
				<p><a href="faq.php">Frequently Asked Questions</a></p>
				<p><a href="privacy_policy.php">Privacy Policy</a></p>
				<p><a href="terms_and_conditions.php">Terms and Conditions</a></p>
			</div>
			<div class="split">
				<h1>Account</h1>
				<?php if(!$_SESSION['log']) : ?>
				<p><a href="user_reg.php">Sign Up</a></p>
				<p><a href="emp_user_reg.php?emp_yes=true">Employers</a></p>
				<?php else : ?>
				<p><a href="fav_jobs.php">Favourited Jobs</a></p>
				<?php if( $_SESSION['ver'] != 2 ) : ?>
				<p><a href="verify_account.php">Verify Account</a></p>
				<?php endif ; ?>
				<p><a href="update_acc.php">Update Account Info</a></p>
				<p><a href="upd_password.php">Change Password</a></p>
				<?php if($_SESSION['es'] != 2) : ?>
				<p><a href="applied_jobs.php">Jobs Applied For</a></p>
				<p><a href="quick_cv.php">Quick CV</a></p>
				<?php else : ?>
				<p><a href="employer_profile.php">Employer Profile</a></p>
				<?php endif ; ?>
				<?php endif ; ?>
				<p><a href="unsubscribe.php">Unsubscribe from Emails</a></p>
			</div>
			<div class="split">
				<h1>Contact</h1>
				<p><a href="support.php">Support</a></p>
				<p><a href="https://www.facebook.com/pages/Jobsession/217102721823067" target="_blank"><span class="icon-facebook2"></span> Facebook</a></p>
				<p><a href="http://instagram.com/j0bsession" target="_blank"><span class="icon-instagram"></span> Instagram</a></p>
				<p><a href="https://twitter.com/j0bsession" target="_blank"><span class="icon-twitter2"></span> Twitter</a></p>
				<p><a href="support.php?bug=true">Report a Bug</a></p>
			</div>
		</div><!-- footer_bar -->
	</div><!-- center_it -->

<!--
<div class="bug_bar">
	<div class="bug_bar_center">
		<marquee><p>Bugs</p></marquee>
	</div>
</div>
-->

<a href="#"><div class="top"><p><span class="icon-arrow-up4"></span></p><p>TOP</p></div></a>

</div><!-- wrapper -->

<?php if( !empty($_POST['delete_job'] ) ) : ?>
<div class="black_screen">
	<div class="delete_checker">
		<p>Are you sure you wish to delete this job?</p>
		<form action='employer_profile.php' method='POST'>
			<input type='hidden' value='<?php echo $_POST['delete_job']; ?>' name='delete_job_yes'>
			<input type='submit' value='CONFIRM' class='submit_button , yes'>
		</form>
		<form action='employer_profile.php' method='POST'>
			<input type='hidden' value='true' name='delete_job_no'>
			<input type='submit' value='CANCEL' class='submit_button , no'>
		</form>
	</div><!-- delete_checker -->
</div><!-- black_screen -->
<?php endif; ?>

<div class="app_holder"></div>

<?php if(empty($no_search)){ include('includes/refine_query.php'); } ?>
<?php mysqli_close( $dbc ); ?>
</body>
</html>