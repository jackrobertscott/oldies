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

if( !$_SESSION['log'] ){
	header('Location: no_access.php');
	exit();
}

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ){

	if($_POST['reset_pass'] == true){

		$new_salt = SHA1( time() ); //make new salt
		$pass_code = time() . rand(); //Get a random code to make temporary password
		$pass_email = SHA1( $pass_code ); //encrypt the password once to send to person via email 
		$pass_upd = SHA1( $pass_email . 	$new_salt ); 	//encrypt it a second time to add to database as when the password is submitted to log in
											//as the password is encrypted when checked against the database
		$pass_query = "UPDATE Users SET Password = '$pass_upd' WHERE Id = '$id'";
		$pass_action = mysqli_query( $dbc , $pass_query );
		$salt_query = "UPDATE Users SET Salt = '$new_salt' WHERE Id = '$id'";
		$salt_action = mysqli_query( $dbc , $salt_query );
		if(!$pass_action || !$salt_action){
			$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
		}else{
			$email_pass = new PHPMailer();
			$email_pass->isHTML(true);
			$email_pass->Subject   = 'Jobsession - Password Reset';
			$email_pass->FromName   = 'Jobsession';
			$email_pass->Body      = $email_des['header'] .
								'<p>Your Password for Jobsession has been reset.</p>' .
								'<p>Your new password is : ' . $pass_email . '</p>' .
								$email_des['footer'] ;
			$email_pass->AddAddress( $user_array['Email'] );
			//check if the email_pass was successfully sent
			if(!$email_pass->send()) {
				$errors[] = "There is something wrong with our servers, your application was unable to be sent.";
			}else{
				$sent_fail = true;
			}
		}

	}else{
	
		if( empty( $_POST['password1'] ) || empty( $_POST['password2'] ) ){
			$errors[] = 'Your new password input box is empty';
		} elseif( $_POST['password1'] == $_POST['password2'] ) {
			$new_password = mysqli_real_escape_string( $dbc , trim( $_POST['password1'] ) );
			$new_password = strip_tags($new_password);
		} else {
			$errors[] = 'Your new passwords do not match';
		}
		
		if( empty( $_POST['current_pass'] ) ){
			$errors[] = 'Your Current Password input box is empty';
		} else {
			$old_password = mysqli_real_escape_string( $dbc , trim( $_POST['current_pass'] ) );
			$old_password = strip_tags($old_password);
		}
		
		if( empty($errors) ){ 
			$message = change_password( $new_password , $old_password , $dbc );
			if(!empty($message)){
				$errors[] = $message;
			}
		}
	
	}

}

$page_title = "Change Password";
include('includes/header.php'); 
?>

<?php include('includes/log_in_bar.php'); ?>

<div class="center_it">
	<div class="main_content" >
	
	<?php include ('includes/sidebar.php'); ?>
	
		<div class="main_right">

			<?php if( $_SESSION['log'] ) : ?>

				<?php if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) : ?>

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
								if(!$sent_fail) {
								
									echo "<p>Your password has been successfully changed.</p>";
								
								}else{

									echo "<p>Your password has been reset and the new password has been send to your email.</p>";
									echo "<p>The password has been sent to " . $user_array['Email'] . "</p>";

								}
								?>
						
							</div><!-- text -->
						</div><!-- right_art -->

					<?php endif; ?>
		
				<?php endif; ?>
			
				<div class="upd_inf">
					<div class="updating_data_info">
							<p>Change your Password<br><br>It is recomended you use both letters and numbers and have a minimum of 6 charecters</p>
					</div><!-- updating_data_info -->
					<form action="upd_password.php" method="POST">
						<div class="half_box_long">
							<p>Insert Current Password (old) : </p>
						</div>
						<input class="inf_long" placeholder="Current Password" type="password" name="current_pass">
						<div class="half_box_long">
							<p>Insert the New Password : </p>
						</div>
						<input class="inf_long" placeholder="New Password" type="password" name="password1">
						<div class="half_box_long">
							<p>Re-Insert New Password : </p>
						</div>
						<input class="inf_long" placeholder="New Password" type="password" name="password2">
						<input type="submit" class="submit_inf , submit_button" value="Save">
					</form>
				</div><!-- upd_inf -->

				<?php if(!$sent_fail) :?>

					<div class="upd_inf">
						<form action="upd_password.php" method="POST">
							<input type="hidden" name="reset_pass" value="true">
							<input type="submit" class="submit_inf , submit_button" value="Forgot Password? - Send Me A New One" style="background-color: #2aa63f;">
						</form>
					</div><!-- upd_inf -->

				<?php endif ; ?>

				<?php else : ?>
				
					<div class="right_art">		
						<div class="text">
				
							<p>You are not currently signed into an account.</p>
							<p>Please Sign into an account <a href="log_in.php">Here</a>.</p>
							<p>Or register a new account <a href="user_reg">Here</a></p>

						</div><!-- text -->
					</div><!-- right_art -->

				<?php endif; ?>
				
		</div><!-- main_right -->
	</div><!-- main_content -->
</div><!-- center_it -->

<?php
include('includes/footer.php');
?>