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

$page_title = "No Access";
include('includes/header.php');
?>

<?php include('includes/log_in_bar.php'); ?>

<div class="center_it">
	<div class="main_content" >
	
	<?php include ('includes/sidebar.php'); ?>
	
		<div class="main_right">

			<div class="right_art errors">
				<div class="text">
				
				<p>You do NOT have access to that page.</p>
				<p>Return <a href="index.php">Home</a></p>

				</div><!-- text -->
			</div><!-- right_art -->

		</div><!-- main_right -->
	</div><!-- main_content -->
</div><!-- center_it -->

<?php 
include('includes/footer.php');
?>