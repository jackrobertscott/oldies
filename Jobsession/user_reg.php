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

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ){

	if( empty( $_POST['username'] ) ){
		$errors[] = 'Your username input box is empty';
	} else {
	
		$username = mysqli_real_escape_string( $dbc , trim( $_POST['username'] ) );
		$username = strip_tags($username);
	
		$checker_user = checkIfGoneUser( $username , $dbc );
		if( $checker_user ){
			if( $checker_user === true ){
				$errors[] = 'That Username has already been taken';
			}else{
				$errors[] = $checker_user ;
			}
		}
		
	}
	
	if( empty( $_POST['email'] ) ){
		$errors[] = 'Your email input box is empty';
	} else {
	
		$email = mysqli_real_escape_string( $dbc , trim( $_POST['email'] ) );
		$email = strip_tags($email);
		
		$checker_email = checkIfGoneEmail( $email , $dbc );
		if( $checker_email ){
			if( $checker_email === true ){
				$errors[] = 'That email is already in use';
			}else{
				$errors[] = $checker_email ;
			}
		}
		
		$pattern = '/\b[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}\b/';
		if( !preg_match( $pattern , $email ) ){
			$errors[] = 'Your email address is in the incorrect format';
			$email = NULL;
		}
		
	}
	
	if( empty( $_POST['password1'] ) || empty( $_POST['password2'] ) ){
		$errors[] = 'Your password input box is empty';
	} elseif( $_POST['password1'] == $_POST['password2'] ) {
		$password = mysqli_real_escape_string( $dbc , trim( $_POST['password1'] ) );
		$password = strip_tags($password);
	} else {
		$errors[] = 'Your passwords do not match';
	}

	if( $_POST['pripol'] != true ){
		$errors[] = 'You must agree to our <a href="privacy_policy.php" target="_blank">Privacy Policy</a> and <a href="terms_and_conditions.php" target="_blank">Terms and Conditions</a> before you can become a member.';
	}
	
	if( empty($errors) ){
		$message = submitData( $username , $password , $email , $dbc );
		if(!empty($message)){
			$errors[] = $message;
		}
	}

}

$page_title = "Sign Up";
include('includes/header.php'); 
?>

<?php include('includes/log_in_bar.php'); ?>

<div class="center_it">
	<div class="main_content" >
	
	<?php include ('includes/sidebar.php'); ?>
	
		<div class="main_right">

				<?php if( !$_SESSION['log'] ) : ?>

					<?php if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' && !empty( $errors ) ) : ?>
						
						<div class="right_art errors">
							<div class="text">
								<?php
		    					echo "<p>The following errors were found with your submission :</p>";
								foreach( $errors as $error ){ echo "<p>" . $error . "</p>" ; }
		    					?>
		    				</div><!-- text -->
		    			</div><!-- right_art -->
    		
    				<?php endif; ?>

					<div class="right_art">
						<div class="updating_data_info">
							<p>Create an account - It takes only a few seconds</p>
						</div><!-- updating_data_info -->
						<form action="user_reg.php" method="POST">
							<div class="hold_reg_inputs">
								<input class="reg_input" placeholder="Username" type="text" name="username" value="<?php if(isset($_POST['username'])){ echo $_POST['username']; } ?>">
								<div class="some_space">
									<input class="reg_input" placeholder="Password" type="password" name="password1" value="<?php if(isset($_POST['password1'])){ echo $_POST['password1']; } ?>">
								</div><!-- some_space -->
								<div class="some_space">
									<input class="reg_input" placeholder="Confirm Password" type="password" name="password2" value="<?php if(isset($_POST['password2'])){ echo $_POST['password2']; } ?>">
								</div><!-- some_space -->
								<div class="some_space">
									<input class="reg_input" placeholder="Email" type="text" name="email" value="<?php if(isset($_POST['email'])){ echo $_POST['email']; } ?>">
								</div><!-- some_space -->
								<div class="some_space" style="width:500px;">
									<label>
										<p><input type="checkbox" name="pripol" style="display:inline;" value="true">
										By selecting this you agree to our <a href="privacy_policy.php" target="_blank">Privacy Policy</a> and <a href="terms_and_conditions.php" target="_blank">Terms and Conditions</a></p>
									</label>
								</div><!-- some_space -->
							</div>
							<input type="submit" class="submit_button , reg_submit" value="Create Account">
						</form>
					</div><!-- right_art -->
    		
		    		<div class="right_art">		
						<div class="text">
						
							<p>Allready have an account? sign in <a href="log_in.php">Here</a></p>

						</div><!-- text -->
					</div><!-- right_art -->

				<?php elseif( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) : ?>
				
					<div class="right_art">		
						<div class="text">
						
							<p><?php echo $_SESSION['user'] ; ?>, congratulations on creating your Jobsession account. We are happy to have you on board.</p>
							<br><p>A message with your verification details has been sent to your account email address.</p>
							<br><p>To complete your registration, just <a href="verify_account.php">VERIFY</a> your account.</p>

						</div><!-- text -->
					</div><!-- right_art -->

				<?php else : ?>

					<div class="right_art">		
						<div class="text">
							
							<p>You are signed into the account <?php echo $_SESSION['user'] ; ?></p>
							<?php if($_SESSION['ver'] != 2){ echo '<br><p>To access all of Jobsessions features, <a href="verify_account.php">VERIFY</a> your account.</p>'; } ?>

						</div><!-- text -->
					</div><!-- right_art -->

				<?php endif; ?>
				
		</div><!-- main_right -->
	</div><!-- main_content -->
</div><!-- center_it -->

<?php
include('includes/footer.php');
?>