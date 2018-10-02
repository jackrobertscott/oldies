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

if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST'){
	
	if( empty( $_POST['unsubEmail'] ) ){
		$errors[] = 'Your Email Adress input box is empty';
	} else {
		$unsubEmail = mysqli_real_escape_string( $dbc , trim( $_POST['unsubEmail'] ) );
		$unsubEmail = strip_tags($unsubEmail);
		$pattern = '/\b[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}\b/';
		if( !preg_match( $pattern , $unsubEmail ) ){
			$errors[] = 'Your Email Address is in the incorrect format';
			$unsubEmail = NULL;
		}
	}
	
	if( empty( $_POST['unsubPass'] ) ){
		$errors[] = 'Your Password input box is empty';
	} else {
		$unsubPass = mysqli_real_escape_string( $dbc , trim($_POST['unsubPass']) );
		$unsubPass = strip_tags($unsubPass);
	}

	if( empty($errors) ){
		$message = unsubscribe( $dbc , $unsubEmail , $unsubPass );
		if(!empty($message)){
			$errors[] = $message;
		}
	}
	
}

$page_title = "Unsubscribe";
include('includes/header.php');
?>

<?php include('includes/log_in_bar.php'); ?>

<div class="center_it">
	<div class="main_content" >
	
	<?php include ('includes/sidebar.php'); ?>
	
		<div class="main_right">
		
			<?php if(!empty($errors)): ?>

				<div class="right_art errors">
					<div class="text">
						<p>The following errors have been found</p>
						<?php foreach( $errors as $err ){ echo "<p>$err</p>" ; } ?>
					</div><!-- text -->
				</div><!-- right_art -->

			<?php endif; ?>

			<?php if($_SERVER[ 'REQUEST_METHOD' ] == 'POST' && empty($errors)): ?>

				<div class="right_art">
					<div class="text">
						<p>You have been successfully unsubscribed from Jobsession news letters.</p>
					</div><!-- text -->
				</div><!-- right_art -->

			<?php else: ?>

				<div class="upd_inf">
					<div class="updating_data_info">
							<p>Insert your details bellow and click unsubscribe to stop receiving news letters from Jobsession.</p>
					</div><!-- updating_data_info -->
					<form action="unsubscribe.php" method="POST">
						<div class="half_box_long">
							<p>Insert your Email Address:</p>
						</div>
						<input class="inf_long" placeholder="Email Address" type="text" name="unsubEmail" value="<?php if(isset($_POST['unsubEmail'])){ echo $_POST['unsubEmail']; } ?>">
						<div class="half_box_long">
							<p>Insert your Password : </p>
						</div>
						<input class="inf_long" placeholder="Password" type="password" name="unsubPass">
						<input type="submit" class="submit_inf , submit_button" value="Unsubscribe">
					</form>
				</div><!-- upd_inf -->

			<?php endif; ?>

		</div><!-- main_right -->
	</div><!-- main_content -->
</div><!-- center_it -->

<?php
include('includes/footer.php');
?>