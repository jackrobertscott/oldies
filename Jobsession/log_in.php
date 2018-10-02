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

if ( ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') && (!$_SESSION['log']) ){
	
	if( empty( $_POST['username'] ) ){
		$errors[] = 'Your username input box is empty';
	} else {
		$username = mysqli_real_escape_string( $dbc , trim($_POST['username']) );
		$username = strip_tags($username);
	}
	
	if( empty( $_POST['password'] ) ){
		$errors[] = 'Your password input box is empty';
	} else {
		$password = mysqli_real_escape_string( $dbc , trim($_POST['password']) );
		$password = strip_tags($password);
	}

	if( empty($errors) ){
		$message = checkUser( $_POST['keepin'] , $username , $password , $dbc );
		if(!empty($message)){
			$errors[] = $message;
		}
	}
	
}

$page_title = "Log In";
include('includes/header.php');
?>

<?php include('includes/log_in_bar.php'); ?>

<div class="center_it">
	<div class="main_content" >
	
	<?php include ('includes/sidebar.php'); ?>
	
		<div class="main_right">
		
			<?php if( !empty($errors) ) : ?>

				<div class="right_art errors">
					<div class="text">
						<p>The following errors have been found</p>
						<?php foreach( $errors as $err ){ echo "<p>$err</p>" ; } ?>
					</div><!-- text -->
				</div><!-- right_art -->

			<?php else : ?>

				<div class="right_art">
					<div class="text">
				
						<?php
						if( $_SESSION['log'] ){
							echo "<p>Welcome " . $_SESSION['user'] . "!</p>";
						}else{
							echo "<p>Please insert values above to sign in</p>";
						}
						?>
				
					</div><!-- text -->
				</div><!-- right_art -->

			<?php endif; ?>
			
			<?php if( $_SESSION['ver'] != 2 && $_SESSION['log'] ) : ?>
			
				<div class="upd_inf">
					<div class="updating_data_info">
						<p>Enter the verification code that was sent to your email address. [It is possible that the email was filtered as spam, if you are unable to find it in your inbox. Otherwise click <a href="verify_account.php?resend=true">HERE</a> to resend the verification email.]</p>
					</div><!-- updating_data_info -->
					<form action="verify_account.php" method="POST">
						<div class="half_box_long">
							<p>Verification code : </p>
						</div>
						<input class="inf_long" placeholder="Code" type="text" name="theCode">
						<input type="submit" class="submit_inf , submit_button" value="Submit Code">
					</form>
				</div><!-- upd_inf -->
			
			<?php endif ; ?>

		</div><!-- main_right -->
	</div><!-- main_content -->
</div><!-- center_it -->

<?php
include('includes/footer.php');
?>