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

if( $_SESSION['es'] == 2 ){
	header('Location: employer_profile.php');
	exit();
}

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ){
	
	//Create the Arrays for the data collection to be sent to the update function
	$user_details = array();
	$emp_details = array();

	$image_name = $_FILES['file_sub']['name'];
	$image_type = $_FILES['file_sub']['type'];
	$image_size = $_FILES['file_sub']['size'];
	$image_tmp = $_FILES['file_sub']['tmp_name'];
	$image_error = $_FILES['file_sub']['error'];
	
	$submit_ext = array('png','jpg','jpeg');
	$image_ext = strtolower(end(explode('.' , $image_name)));
	
	if( !empty($image_name) ){
	
		if(!in_array($image_ext, $submit_ext)){
			$errors[] = 'Your image is of an incorrect file type.';
		}
		
		if($image_size > 2000000){
			$errors[] = 'Your image is beyond the maximum file size (2MB)';
		}
	
	}
	
	if( empty( $_POST['fname'] ) ){
		$errors[] = 'Your first name input box is empty';
	} else {
		$fname = mysqli_real_escape_string( $dbc , trim( $_POST['fname'] ) );
		$fname = strip_tags($fname);
	}
	
	if( empty( $_POST['lname'] ) ){
		$errors[] = 'Your last name input box is empty';
	} else {
		$lname = mysqli_real_escape_string( $dbc , trim( $_POST['lname'] ) );
		$lname = strip_tags($lname);
	}
	
	if( empty( $_POST['companyname'] ) ){
		$errors[] = 'Your Company name input box is empty';
	} else {
		$companyname = mysqli_real_escape_string( $dbc , trim( $_POST['companyname'] ) );
		$companyname = strip_tags($companyname);
	}
	
	$curl = mysqli_real_escape_string( $dbc , trim( $_POST['curl'] ) );
	$curl = strip_tags($curl);

	if( !empty( $_POST['email'] ) ){
	
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
	
	if( empty($_POST['phone']) ){
		$errors[] = 'Your Phone number input box is empty';
	} else {
		if( !is_numeric( $_POST['phone'] ) ){
			$errors[] = 'Your primary phone number must be numbers only (check for spaces).';
		} else {
			$phone = mysqli_real_escape_string( $dbc , trim($_POST['phone']) );
			$phone = htmlentities($phone);
		}
	}
	
	if( !empty($_POST['cphone']) ){
		if( !is_numeric( $_POST['cphone'] ) ){
			$errors[] = 'Your company phone number must be numbers only (check for spaces).';
		} else {
			$cphone = mysqli_real_escape_string( $dbc , trim($_POST['cphone']) );
			$cphone = htmlentities($cphone);
		}
	}

	if( empty( $_POST['address1'] ) ){
		$errors[] = 'Your first address input box is empty';
	} else {
		$address1 = mysqli_real_escape_string( $dbc , trim( $_POST['address1'] ) );
		$address1 = strip_tags($address1);
	}

	if( empty($_POST['address2']) ){
		$errors[] = 'Your Post Code input box is empty';
	} else {
		if( !is_numeric( $_POST['address2'] ) ){
			$errors[] = 'Your primary phone number must be numbers only (check for spaces).';
		} else {
			$address2 = mysqli_real_escape_string( $dbc , trim($_POST['address2']) );
			$address2 = htmlentities($address2);
		}
	}

	$user_details['Phone'] = $phone;
	$user_details['Address1'] = $address1;
	$user_details['Address2'] = $address2;
	$emp_details['CompanyPhone'] = $cphone;
	$emp_details['EmailForApplications'] = $emailapp;
	$emp_details['CompanyURL'] = $curl;
	$emp_details['FName'] = $fname;
	$emp_details['LName'] = $lname;
	$emp_details['CompanyName'] = $companyname;

	if( empty($errors) ){
		$checker_employer = check_if_employer( $dbc );
		if( !empty($checker_employer) ){
			if( $checker_employer === true ){
				$errors[] = 'You are already a registered employer';
			}else{
				$errors[] = $checker_employer ;
			}
		}else{
			$message = build_employer( $dbc );
			$message = update_account_info( $user_details , $emp_details , $image_tmp , $image_ext , $dbc );
			if(!empty($message)){
				$errors[] = $message;
			}else{
				header('Location: employer_profile.php');
				exit();
			}
		}
	}

}

$page_title = "Register Employer";
include ('includes/header.php');
?>

<?php include('includes/log_in_bar.php'); ?>

<div class="center_it">
	<div class="main_content" >
	
	<?php include ('includes/sidebar.php'); ?>
	
		<div class="main_right">
		
		<?php if( $_SESSION['ver'] != 2 ) : ?>
		
			<div class="right_art">
				<div class="text">
					<p>You must <a href="verify_account.php">VERIFY</a> your account before you can register as an employer</p>
				</div><!-- text -->
    		</div><!-- right_art -->
		
		<?php else : ?>
		
			<?php if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) : ?>
						
				<?php if(!empty($errors) ) : ?>

					<div class="right_art errors">
						<div class="text">
							<p>The following errors have been found:</p>
							<?php foreach( $errors as $err ){ echo "<p>$err</p>" ; } ?>
						</div><!-- text -->
					</div><!-- right_art -->

				<?php else : ?>

					<div class="right_art">
						<div class="text">
							<p>Success! You are now an employer.</p>
						</div><!-- text -->
					</div><!-- right_art -->

				<?php endif; ?>
				
			<?php endif; ?>
    	
    	<?php endif; ?>
		
		<?php if( !$emp_data ) : ?>
		
			<div class="right_art">
				<div class="text">

						<?php echo '<p>Error! : ' . mysqli_error( $dbc ) . '</p>' ; ?>

				</div><!-- text -->
			</div><!-- right_art -->
		<?php endif; ?>
		
		<?php if( $_SESSION['es'] != 2 && $_SESSION['ver'] == 2) : ?>

			<div class="right_art">
				<div class="text">
					<div class="emp_steps_reg done_reg"><p>Setup Account</p></div>
					<div class="emp_steps_reg done_reg"><p>Verify</p></div>
					<div class="emp_steps_reg done_reg"><p>Add Company Info</p></div>
					<div class="emp_steps_reg"><p>Post Job</p></div>
				</div>
			</div>

			<div class="right_art">
				<div class="text">
					<p>Employer Registration</p>
				</div><!-- text -->
    		</div><!-- right_art -->

			<div class="upd_inf">
				<form enctype="multipart/form-data" action="employer_reg.php" method="POST">
					<div class="updating_data_info">
							<p>REQUIRED</p>
					</div><!-- updating_data_info -->
					<div class="half_box_long">
						<p>*First name : </p>
					</div>
					<input class="inf_long" placeholder="First Name" type="text" name="fname" value="<?php if(isset($_POST['fname'])){ echo $_POST['fname']; } ?>">
					<div class="half_box_long">
						<p>*Last name : </p>
					</div>
					<input class="inf_long" placeholder="Last Name" type="text" name="lname" value="<?php if(isset($_POST['lname'])){ echo $_POST['lname']; } ?>">
					<div class="half_box_long">
						<p>*Company name : </p>
					</div>
					<input class="inf_long" placeholder="Company Name" type="text" name="companyname" value="<?php if(isset($_POST['companyname'])){ echo $_POST['companyname']; } ?>">
					<div class="half_box_long">
						<p>*Primary Phone Number : </p>
					</div>
					<input class="inf_long" placeholder="Phone Number" type="text" maxlength="20" name="phone" value="<?php if(isset($_POST['phone'])){ echo $_POST['phone']; }elseif(isset($user_array['Phone'])){ echo $user_array['Phone']; } ?>">
					<div class="half_box_long">
						<p>*Primary Address : </p>
					</div>
					<input class="inf_long" placeholder="Street and Suburb" type="text" maxlength="100" name="address1" value="<?php if(isset($_POST['address1'])){ echo $_POST['address1']; }elseif(isset($user_array['Address1'])){ echo $user_array['Address1']; } ?>">
					<div class="half_box_long">
						<p>*Post Code : </p>
					</div>
					<input class="inf_long" placeholder="Post Code" type="text" maxlength="10" name="address2" value="<?php if(isset($_POST['address2'])){ echo $_POST['address2']; }elseif(isset($user_array['Address2'])){ echo $user_array['Address2']; } ?>">
					<div class="updating_data_info">
						<p>OPTIONAL</p>
					</div><!-- updating_data_info -->
					<div class="half_box_long">
						<p>Alternate Email for Job Applications :</p>
					</div>
					<input class="inf_long" placeholder="If not specified, will go to main email" type="text" name="email" value="<?php if(isset($_POST['email'])){ echo $_POST['email']; }elseif(isset($emp_array['EmailForApplications'])){ echo $emp_array['EmailForApplications']; } ?>">
					<div class="half_box_long">
						<p>Alternate Company Phone Number : </p>
					</div>
					<input class="inf_long" placeholder="Company Phone Number" type="text" maxlength="20" name="cphone" value="<?php if(isset($_POST['cphone'])){ echo $_POST['cphone']; }elseif(isset($emp_array['CompanyPhone'])){ echo $emp_array['CompanyPhone']; } ?>">
					<div class="half_box_long">
						<p>Website URL : </p>
					</div>
					<input class="inf_long" placeholder="Website URL" type="text" maxlength="100" name="curl" value="<?php if(isset($_POST['curl'])){ echo $_POST['curl']; }elseif(isset($emp_array['CompanyURL'])){ echo $emp_array['CompanyURL']; } ?>">
					<div class="updating_data_info">
						<p>Upload a company logo to attract the Job seekers and let them know who you are.<br>Recomended Dimensions [ 1 : 1 ]</p>
					</div><!-- updating_data_info -->
					<div class="half_box_long">
						<p><?php if( !empty($errors) && !empty($_POST['file_sub']) ){ echo 'You must reupload your logo' ; }else{ echo 'Upload a Company Logo.' ; } ?><br>The Image must be .jpeg .jpg or .png </p>
					</div>
					<input class="inf_long" type="file" name="file_sub" value="Image file">
					<input type="submit" class="submit_inf , submit_button" style="margin-top:26px;" value="Activate Employer Status">
				</form>
			</div><!-- upd_inf -->

		<?php endif; ?>
		
		</div><!-- main_right -->
	</div><!-- main_content -->
</div><!-- center_it -->

<?php
include ('includes/footer.php');
?>