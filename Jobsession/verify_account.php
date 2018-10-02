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

if($_SESSION['emp_app'] && $_SESSION['ver'] == 2){
	header('Location: employer_reg.php');
	exit();
}

if ( isset($_GET['emp_yes']) ){
	$_SESSION['emp_app'] = true ;
}

if( empty($user_array['VerCode']) && $user_array['Verified'] != 2 && $_SESSION['log'] ){
	$verificationCode = SHA1( time() );
	$update_vercode = "UPDATE Users SET VerCode = '$verificationCode' WHERE Id = '$id'";
	if(!mysqli_query($dbc , $update_vercode)){
		$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
	}
	$phpemail = new PHPMailer();
	$phpemail->isHTML(true);
	$phpemail->Subject   = 'Jobsession Account Verification';
	$phpemail->FromName   = 'Jobsession';
	$phpemail->Body      = $email_des['header'] .
			'<h3>Welcome to Jobsession!</h3>' .
			'<p>To verify your account, click this link: <a href="http://jobsession.com.au/verify_account.php?ver_link_code=' . $verificationCode . '">Verify Account</a>' .
			'<p>Or enter your verification code in manualy: ' . $verificationCode . '</p>' .
			'<p>To verify your account, enter the above code into the verification code input box and press submit! Easy!</p>' .
			$email_des['footer'] ;
	$phpemail->AddAddress( $user_array['Email'] );
	//check if the email was successfully sent
	if(!$phpemail->send()) {
		$message = "There is something wrong with our servers, your verification email was unable to be sent to " . $useremail;
		return $message;
	}else{
		$resend_done = true ;
	}
}
//I have made it so that you just need to add a link in the verification email 
//That matches format <a href="THISURL.com.au?ver_link_code=THEVERIFICATIONCODE"></a>
if( $_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET['ver_link_code']) ){
	if( $user_array['VerCode'] !== $_GET['ver_link_code'] ){
		$errors[] = 'The verification code and the code recieved do NOT match';
	} else {
		$update_ver = "UPDATE Users SET Verified = '2' WHERE Id = '$id'";
		if(!mysqli_query($dbc , $update_ver)){
			$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
		}else{
			$user_array['Verified'] = 2 ;
			$_SESSION['ver'] = 2 ;
			if($_SESSION['emp_app'] == true){
				header('Location: employer_reg.php');
				exit();
			}
		}
	}
}elseif( $_SERVER[ 'REQUEST_METHOD' ] == 'GET' && $_GET['resend'] == true && $user_array['Verified'] != 2 && $_SESSION['log'] ){
	
	$phpemail = new PHPMailer();
	$phpemail->isHTML(true);
	$phpemail->Subject   = 'Jobsession Account Verification';
	$phpemail->FromName   = 'Jobsession';
	$phpemail->Body      = $email_des['header'] .
			'<h3>Welcome to Jobsession!</h3>' .
			'<p>To verify your account, click this link: <a href="http://jobsession.com.au/verify_account.php?ver_link_code=' . $verificationCode . '">Verify Account</a>' .
			'<p>Or enter your verification code in manualy: ' . $verificationCode . '</p>' .
			'<p>To verify your account, enter the above code into the verification code input box and press submit! Easy!</p>' .
			$email_des['footer'] ;
	$phpemail->AddAddress( $user_array['Email'] );
	//check if the email was successfully sent
	if(!$phpemail->send()) {
		$message = "There is something wrong with our servers, your verification email was unable to be sent to " . $useremail;
		return $message;
	}else{
		$resend_done = true ;
	}
	
}elseif( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ){
	
	if( empty( $_POST['theCode'] ) ){
		$errors[] = 'The verification code input box was left empty';
	} else {
		$theCode = mysqli_real_escape_string( $dbc , trim($_POST['theCode']) );
		$theCode = strip_tags($theCode);
	}
	
	$id = $_SESSION['id'];
	
	if( $user_array['VerCode'] !== $theCode ){
		$errors[] = 'The verification code and the code you entered do NOT match';
	} else {
		$update_ver = "UPDATE Users SET Verified = '2' WHERE Id = '$id'";
		if(!mysqli_query($dbc , $update_ver)){
			$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
		}else{
			$user_array['Verified'] = 2 ;
			$_SESSION['ver'] = 2 ;
			if($_SESSION['emp_app'] == true){
				header('Location: employer_reg.php');
				exit();
			}
		}
	}
		
}

$page_title = "Verify Account";
include ('includes/header.php');
?>
		
<?php include('includes/log_in_bar.php'); ?>

<div class="center_it">
	<div class="main_content" >
	
	<?php include ('includes/sidebar.php'); ?>
	
		<div class="main_right">
		
				<?php if( $resend_done == true ) : ?>

					<div class="right_art">
						<div class="text">
							<p>A verification email has been resent to the email address : <?php echo $user_array['Email'] ; ?></p>
						</div><!-- text -->
					</div><!-- right_art -->

				<?php elseif ( ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') && ( !empty($errors) ) ) : ?>

					<div class="right_art errors">
						<div class="text">
							<?php	
							echo "<p>The following errors were found with your submission :</p>";
							foreach( $errors as $error ){ echo "<p>" . $error . "</p>" ; }		
							?>
						</div><!-- text -->
					</div><!-- right_art -->

				<?php elseif ( $_SESSION['ver'] == 2 ) : ?>

					<div class="right_art">
						<div class="text">
							<p>Your account has been Verified.</p>
						</div><!-- text -->
					</div><!-- right_art -->

				<?php elseif( !$_SESSION['log'] ) : ?>

					<div class="right_art">
						<div class="text">
							<p>Please log into your account to verify it.</p>
						</div><!-- text -->
					</div><!-- right_art -->

				<?php else : ?>
			
					<?php if($_SESSION['emp_app']) : ?>

						<div class="right_art">
							<div class="text">
								<div class="emp_steps_reg done_reg"><p>Setup Account</p></div>
								<div class="emp_steps_reg done_reg"><p>Verify</p></div>
								<div class="emp_steps_reg"><p>Add Company Info</p></div>
								<div class="emp_steps_reg"><p>Post Job</p></div>
							</div>
						</div>

					<?php endif ; ?>

					<div class="upd_inf">
						<div class="updating_data_info">
							<p>Enter the verification code that was sent to your email address.</p>
							<br><p>If you are unable to find it in your inbox, it is possible that the email was filtered as spam.</p>
							<br><p>To resend the verification email, click <a href="verify_account.php?resend=true">HERE</a></p>
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
include ('includes/footer.php');
?>