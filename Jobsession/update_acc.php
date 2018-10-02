<?php
session_start();
require('../../connect_db.php');
require('includes/first_declarations.php');
require('includes/register_functions.php');
require('includes/job_sub_functions.php');
require('includes/PHPMailer/PHPMailerAutoload.php');
include('includes/grab_all_data.php');

if ( ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') && ($_SESSION['log']) ){

	//Create the Arrays for the data collection to be sent to the update function
	$user_details = array();
	$emp_details = array();

	if($_SESSION['es'] == 2){

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
		
		if( empty($_POST['phone']) ){
			$errors[] = 'Your Phone number input box is empty';
		} else {
			if( !is_numeric( $_POST['phone'] ) ){
				$errors[] = 'Your phone number must be numbers only.(check for spaces)';
			} else {
				$phone = mysqli_real_escape_string( $dbc , trim($_POST['phone']) );
				$phone = htmlentities($phone);
			}
		}
		
		if(!empty($_POST['cphone'])){
			if( !is_numeric($_POST['cphone']) && !empty($_POST['cphone']) ){
				$errors[] = 'Your company phone number must be numbers only.(check for spaces)';
			} else {
				$cphone = mysqli_real_escape_string( $dbc , trim($_POST['cphone']) );
				$cphone = htmlentities($cphone);
			}
		}
		
		if( !empty( $_POST['emailapp'] ) ){
			$emailapp = mysqli_real_escape_string( $dbc , trim( $_POST['emailapp'] ) );
			$emailapp = strip_tags($emailapp);
			if( $emailapp !== $emp_array['EmailForApplications'] ){
				if( checkIfGoneEmail( $emailapp , $dbc ) ){
					$errors[] = 'That Email Address is already in use';
				}
				$pattern = '/\b[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}\b/';
				if( !preg_match( $pattern , $emailapp ) ){
					$errors[] = 'Your email address is in the incorrect format';
					$emailapp = NULL;
				}
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
				$errors[] = 'Your Post Code must be numbers only.(check for spaces)';
			} else {
				$address2 = mysqli_real_escape_string( $dbc , trim($_POST['address2']) );
				$address2 = htmlentities($address2);
			}
		}
		
		$curl = mysqli_real_escape_string( $dbc , trim($_POST['curl']) );
		$curl = strip_tags($curl);
		
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
		
		if( empty( $_POST['cname'] ) ){
			$errors[] = 'Your Company name input box is empty';
		} else {
			$cname = mysqli_real_escape_string( $dbc , trim( $_POST['cname'] ) );
			$cname = strip_tags($cname);
		}
		
		if( strlen($address1) > 100 ){
			$errors[] = 'Your first address is too long. Please keep it under 100 charecters.';
		}
		
		if( strlen($address2) > 10 ){
			$errors[] = 'Your second address is too long. Please keep it under 100 charecters.';
		}

		$user_details['Phone'] = $phone;
		$user_details['Address1'] = $address1;
		$user_details['Address2'] = $address2;
		$emp_details['CompanyPhone'] = $cphone;
		$emp_details['EmailForApplications'] = $emailapp;
		$emp_details['CompanyURL'] = $curl;
		$emp_details['FName'] = $fname;
		$emp_details['LName'] = $lname;
		$emp_details['CompanyName'] = $cname;
	
	} else {

		if(!empty($_POST['phone'])){
			if( !is_numeric($_POST['phone']) && !empty($_POST['phone']) ){
				$errors[] = 'Your phone number must be numbers only (check for spaces).';
			} else {
				$phone = mysqli_real_escape_string( $dbc , trim($_POST['phone']) );
				$phone = htmlentities($phone);
			}
		}

		if(!empty($_POST['address2'])){
			if( !is_numeric($_POST['address2']) && !empty($_POST['address2'])){
				$errors[] = 'Your Post Code must be numbers only (check for spaces).';
			} else {
				$address2 = mysqli_real_escape_string( $dbc , trim($_POST['address2']) );
				$address2 = htmlentities($address2);
			}
		}

		$address1 = mysqli_real_escape_string( $dbc , trim( $_POST['address1'] ) );
		$address1 = strip_tags($address1);

		$user_details['Phone'] = $phone;
		$user_details['Address1'] = $address1;
		$user_details['Address2'] = $address2;

	}

	if( empty($errors) ){
		$message = update_account_info( $user_details , $emp_details , $image_tmp , $image_ext , $dbc );
		if(!empty($message)){
			$errors[] = $message;
		}
	}

}

//The grab all data file must be called after the above php code so that if an input is removed, it is removed in the inputs displayed.
//having grab_all_data above would allow a variable to be stored in the user_array, then if removed through post would still be held in the 
//remove through user_array even though it had been removed and would as such show on the page display in the text box. 
//As the above code references the variables in grab_all_data it has been decided to call it twice. the second should overwrite the first
include('includes/grab_all_data.php');
$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$_SESSION['url1'] = $url;

$page_title = "Update Account";
include ('includes/header.php');

?>


<?php include('includes/log_in_bar.php'); ?>

<div class="center_it">
	<div class="main_content" >
	
	<?php include ('includes/sidebar.php'); ?>
	
		<div class="main_right">
		
			<?php if ( ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') && ($_SESSION['log']) ) : ?>
		
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
							<p>Your request was successful. Your data has been updated</p>
						</div><!-- text -->
					</div><!-- right_art -->

				<?php endif; ?>
				
			<?php endif; ?>
		
			<?php if( $_SESSION['log'] ) : ?>
		
				<?php if( $_SESSION['es'] == 2 ) : ?>
					<div class="upd_inf">
						<form enctype="multipart/form-data" action="update_acc.php" method="POST">
							<div class="updating_data_info">
								<p>REQUIRED</p>
							</div><!-- updating_data_info -->
							<div class="half_box_long">
								<p>Update your First Name : </p>
							</div>
							<input class="inf_long" placeholder="First Name" type="text" name="fname" value="<?php if(!empty($_POST['fname'])){ echo $_POST['fname']; }elseif(!empty($emp_array['FName'])){ echo $emp_array['FName']; } ?>">
							<div class="half_box_long">
								<p>Update your Last Name : </p>
							</div>
							<input class="inf_long" placeholder="Last Name" type="text" name="lname" value="<?php if(!empty($_POST['lname'])){ echo $_POST['lname']; }elseif(!empty($emp_array['LName'])){ echo $emp_array['LName']; } ?>">
							<div class="half_box_long">
								<p>Update your Company Name : </p>
							</div>
							<input class="inf_long" placeholder="Company Name" type="text" name="cname" value="<?php if(!empty($_POST['cname'])){ echo $_POST['cname']; }elseif(!empty($emp_array['CompanyName'])){ echo $emp_array['CompanyName']; } ?>">
							<div class="half_box_long">
								<p>Update your Primary phone number : </p>
							</div>
							<input class="inf_long" placeholder="Phone Number" type="text" name="phone" value="<?php if(!empty($_POST['phone'])){ echo $_POST['phone']; }elseif(!empty($user_array['Phone'])){ echo $user_array['Phone']; } ?>">
							<div class="half_box_long">
								<p>Update your Address : </p>
							</div>
							<input class="inf_long" placeholder="Street and Suburb" type="text" name="address1" value="<?php if(!empty($_POST['address1'])){ echo $_POST['address1']; }elseif(!empty($user_array['Address1'])){ echo $user_array['Address1']; } ?>">
							<div class="half_box_long">
								<p>Post Code : </p>
							</div>
							<input class="inf_long" placeholder="Post Code" type="text" maxlength="10" name="address2" value="<?php if(!empty($_POST['address2'])){ echo $_POST['address2']; }elseif(!empty($user_array['Address2'])){ echo $user_array['Address2']; } ?>">
							<div class="updating_data_info">
								<p>OPTIONAL</p>
							</div><!-- updating_data_info -->
							<div class="half_box_long">
								<p>Update your Email for Applications : </p>
							</div>
							<input class="inf_long" placeholder="Alternate Email for Applications" type="text" name="emailapp" value="<?php if(!empty($_POST['emailapp'])){ echo $_POST['emailapp']; }elseif(!empty($emp_array['EmailForApplications'])){ echo $emp_array['EmailForApplications']; } ?>">
							<div class="half_box_long">
								<p>Update your Company phone number : </p>
							</div>
							<input class="inf_long" placeholder="Company phone number" type="text" name="cphone" value="<?php if(!empty($_POST['cphone'])){ echo $_POST['cphone']; }elseif(!empty($emp_array['CompanyPhone'])){ echo $emp_array['CompanyPhone']; } ?>">
							<div class="half_box_long">
								<p>Update your Company URL : </p>
							</div>
							<input class="inf_long" placeholder="Company URL" type="text" name="curl" value="<?php if(!empty($_POST['curl'])){ echo $_POST['curl']; }elseif(!empty($emp_array['CompanyURL'])){ echo $emp_array['CompanyURL']; } ?>">
							<div class="updating_data_info">
								<p>Change or Add a Company Logo. Recomended Dimensions [ 1 : 1 ]</p>
							</div><!-- updating_data_info -->
							<div class="half_box_long">
								<p>Change Company Logo : </p>
							</div>
							<input class="inf_long" type="file" name="file_sub" value="Image file">
							<input type="submit" class="submit_inf , submit_button" value="Save">
						</form>
					</div><!-- upd_inf -->
				<?php else : ?>
					<div class="upd_inf">
						<form enctype="multipart/form-data" action="update_acc.php" method="POST">
							<div class="updating_data_info">
								<p>UPDATE</p>
							</div><!-- updating_data_info -->
							<div class="half_box_long">
								<p>Update your Primary phone number : </p>
							</div>
							<input class="inf_long" placeholder="Phone number" type="text" name="phone" value="<?php if(!empty($_POST['phone'])){ echo $_POST['phone']; }elseif(!empty($user_array['Phone'])){ echo $user_array['Phone']; } ?>">
							<div class="half_box_long">
								<p>Update your Address : </p>
							</div>
							<input class="inf_long" placeholder="Street and Suburb" type="text" name="address1" value="<?php if(!empty($_POST['address1'])){ echo $_POST['address1']; }elseif(!empty($user_array['Address1'])){ echo $user_array['Address1']; } ?>">
							<div class="half_box_long">
								<p>Post Code : </p>
							</div>
							<input class="inf_long" placeholder="Post Code" type="text" maxlength="10" name="address2" value="<?php if(isset($_POST['address2'])){ echo $_POST['address2']; }elseif(isset($user_array['Address2'])){ echo $user_array['Address2']; } ?>">
							<input type="submit" class="submit_inf , submit_button" value="Save">
						</form>
					</div><!-- upd_inf -->
				<?php endif; ?>
		
			<?php else : ?>
				<div class="right_art">
					<div class="text">
						<p>You must first sign in before you update your information. </p>
					</div><!-- text -->
				</div><!-- right_art -->
			<?php endif; ?>
		
		</div><!-- main_right -->
	</div><!-- main_content -->
</div><!-- center_it -->

<?php
include ('includes/footer.php');
?>