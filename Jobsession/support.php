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

	if( empty( $_POST['FirstName'] ) ){
		$errors[] = "Please insert in a Name";
	} else {
		$firstname_me = mysqli_real_escape_string( $dbc , trim($_POST['FirstName']) );
		$firstname_me = strip_tags($firstname_me);
	}

	if( empty( $_POST['Email'] ) ){
		$errors[] = "Please insert a email address for contact";
	} else {
		$email_me = mysqli_real_escape_string( $dbc , trim($_POST['Email']) );
		$email_me = strip_tags($email_me);
		$pattern = '/\b[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}\b/';
		if( !preg_match( $pattern , $email_me ) ){
			$errors[] = 'Your email address is in the incorrect format';
			$email = NULL;
		}
	}

	if( empty( $_POST['Subject'] ) ){
		$errors[] = "Please insert in a Subject";
	} else {
		$support_me = mysqli_real_escape_string( $dbc , trim($_POST['Support']) );
	}

	if( empty($_POST['CorQ']) ){
		$errors[] = "Please write us your comment or question";
	} else {
		$corq_me = mysqli_real_escape_string( $dbc , trim($_POST['CorQ']) );
	}

	require_once('includes/recaptchalib.php');
	$privatekey = "6LczTPISAAAAAFPowVqE-uQ4OyqKmnerV_EE7ITb";
	$resp = recaptcha_check_answer ($privatekey,
	                            $_SERVER["REMOTE_ADDR"],
	                            $_POST["recaptcha_challenge_field"],
	                            $_POST["recaptcha_response_field"]);
	if (!$resp->is_valid) {
		$errors[] = "Your verification code was incorrectly submited. Please try again.";
	}

	if($_GET['bug'] == true){
		$from_name = 'Bug Reported';
	}else{
		$from_name = 'Customer Support';
	}

	if( empty($errors) ){
		$email = new PHPMailer();
		$email->isHTML(true);
		$email->Subject   = $support_me;
		$email->FromName  = $from_name;
		$email->Body      = $email_des['header'] .
				'<p>Name : ' . $firstname_me . '</p>' .
				'<p>Email : ' . $email_me . '</p>' .
				'<p>Comment or Question : ' . $corq_me . '</p>' .
				$email_des['footer'] ;
		$email->AddAddress('support@jobsession.com.au');
		if(!$email->send()) {
			$errors[] = "There is something wrong with our servers, your application was unable to be sent.";
		}else{
			$sent_email = true ;
			$email2 = new PHPMailer();
			$email2->isHTML(true);
			$email2->Subject   = $firstname_me . ", Your Jobsession message has been recieved.";
			$email2->FromName   = 'Jobsession';
			$email2->Body      = $email_des['header'] .
					'<p>Thank you for the message.</p>' .
					'<p>We will hopefully get back to you within the next couple of days.</p>' .
					'<p>Yours Sincerely, Jobsession Support Team.</p>' .
					$email_des['footer'] ;
			$email2->AddAddress($email_me);
			$email2->send();
		}
	}

}

$page_title = "Support";
include ('includes/header.php');
?>

<?php include('includes/log_in_bar.php'); ?>

<div class="center_it">
	<div class="main_content" >
	
	<?php include ('includes/sidebar.php'); ?>
	
		<div class="main_right">

			<?php if($_SERVER[ 'REQUEST_METHOD' ] == 'POST') : ?>

				<?php if(!empty($errors)) : ?>
		
					<div class="right_art errors">
						<div class="text">
			
						<?php
						echo "<p>The following things still need to be checked :</p>";
						foreach( $errors as $error ){ echo "<p>" . $error . "</p>" ; }
						?>
			
						</div><!-- text -->
					</div><!-- art_right -->

				<?php elseif($sent_email) : ?>

					<div class="right_art">
						<div class="text">
							<p>Thanks! Your message has been sent to Jobsession. We will hopefully send you a response soon.</p>
						</div><!-- text -->
					</div><!-- art_right -->

				<?php endif ; ?>
		
			<?php endif; ?>

			<script type="text/javascript">
			 var RecaptchaOptions = {
			    theme : 'clean'
			 };
			 </script>

			<div class="upd_inf">
				<?php if($_GET['bug'] == true) : ?>
				<div class="updating_data_info">
						<p>Thanks for your help, we will try to fix the problem as soon as possible.</p>
				</div><!-- updating_data_info -->
				<?php else : ?>
				<div class="updating_data_info">
						<p>Jobsession is customer friendly, contact us anytime.</p>
				</div><!-- updating_data_info -->
				<?php endif; ?>
				<form action="support.php" method="POST">
					<?php if(!empty($user_array['Email'])) : ?>
						<div class="half_box_long" style="width: 640px;padding-top: 10px;">
							<p>Your response will be sent to <?php echo $user_array['Email'] ; ?></p>
							<input type="hidden" name="Email" value="<?php echo $user_array['Email'] ; ?>">
						</div>
					<?php endif ; ?>
					<?php if(empty($emp_array['FName'])) : ?>
						<div class="half_box_long">
							<p>*Name : </p>
						</div>
						<input class="inf_long" placeholder="Name" type="text" name="FirstName" maxlength="32" value="<?php if(!empty($_POST['FirstName'])){ echo $_POST['FirstName']; } ?>">
					<?php else : ?>
						<div class="half_box_long" style="width: 640px;">
							<p>Hey <?php echo $emp_array['FName'] ; ?>, ask us anything or just give us some general feedback on your Jobsession experience.</p>
							<input type="hidden" name="FirstName" value="<?php echo $emp_array['FName'] ; ?>">
						</div>
					<?php endif ; ?>
					<?php if(empty($user_array['Email'])) : ?>
						<div class="half_box_long">
							<p>*Email : </p>
						</div>
						<input class="inf_long" placeholder="example@email.com" type="text" name="Email" maxlength="32" value="<?php if(!empty($_POST['Email'])){ echo $_POST['Email']; } ?>">
					<?php endif ; ?>
					<div class="half_box_long">
							<p>*Subject : </p>
						</div>
					<input class="inf_long" placeholder="Subject" type="text" name="Subject" maxlength="100" value="<?php if(!empty($_POST['Subject'])){ echo $_POST['Subject']; } ?>">
					<?php if($_GET['bug'] == true) : ?>
					<div class="half_box_long" style="height: 28px;width: 640px;">
						<p>*Bug (please describe what is wrong and what page it is located on)</p>
					</div>
					<?php else : ?>
					<div class="half_box_long" style="height: 28px;width: 640px;">
						<p>*Write Jobsession a message, include your Comments and/or Questions</p>
					</div>
					<?php endif; ?>
					<textarea class="inf_long_textarea" maxlength="1000" name="CorQ" style="margin-bottom: 10px;"><?php if(!empty($_POST['CorQ'])){ echo $_POST['CorQ']; } ?></textarea>
					<?php 
					require_once('includes/recaptchalib.php');
					$publickey = "6LczTPISAAAAAJ4kzj1KDOJJEeF5KOmBO2cq6xmQ";
					echo recaptcha_get_html($publickey);
					?>
					<input type="submit" class="submit_inf , submit_button" value="Send">
				</form>
			</div><!-- upd_inf -->

		</div><!-- main_right -->
	</div><!-- main_content -->
</div><!-- center_it -->

<?php 
include('includes/footer.php');
?>