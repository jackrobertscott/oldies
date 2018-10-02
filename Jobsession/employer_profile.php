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

if( !$_SESSION['log'] || $_SESSION['es'] != 2 ){
	header('Location: no_access.php');
	exit();
}

if($_SERVER[ 'REQUEST_METHOD' ] == 'POST'){

	if( !empty($_POST['delete_job_yes']) ){ //delete_job_yes is found in the footer
		$job_code = $_POST['delete_job_yes'] ;
		$delete_job_query = "UPDATE JobSubmit SET Deleted = '1' WHERE QueryCode = '$job_code'";
		$deactivate_job_query = "UPDATE JobSubmit SET Active = '0' WHERE QueryCode = '$job_code'";
		$job_fatal = mysqli_query( $dbc , $delete_job_query );
		$job_fatal_deact = mysqli_query( $dbc , $deactivate_job_query );
		$current_id = $_SESSION['id'];
		$emp_query_kqs = "SELECT JobLimit FROM Employers WHERE UserId = '$current_id'";
		$emp_data_kqs = mysqli_query( $dbc , $emp_query_kqs );
		$emp_var_kqs = mysqli_fetch_assoc( $emp_data_kqs );
		$tick_bef = $emp_var_kqs['JobLimit'];
		$tick_bef -- ; 
		$job_ticker = "UPDATE Employers SET JobLimit = '$tick_bef' WHERE UserId = '$current_id'";
		if( !$job_fatal || !$job_fatal_deact || !$emp_data_kqs || !mysqli_query($dbc , $job_ticker) ){
			$errors[] = '<li><p>There is a promblem with our server : ' . mysqli_error( $dbc ) . '</p></li>';
		}else{
			$job_delete_success = 'Your job has been successfully deleted';
		}
	}

	if( !empty($_POST['wanted_code']) ){
		$wanted_post = $_POST['wanted_code'];
		$get_spef_job = "SELECT * FROM JobSubmit WHERE QueryCode = '$wanted_post'";
		$job_data_query = mysqli_query( $dbc , $get_spef_job );
		if( !$job_data_query ){
			$errors[] = '<li><p>There is a promblem with our server : ' . mysqli_error( $dbc ) . '</p></li>';
		}else{
			$job_data_inputs = mysqli_fetch_assoc( $job_data_query );
			$fav_user_id = $job_data_inputs['Id'];
			$app_query = "SELECT Id FROM AppliedJobs WHERE JobId = '$fav_user_id'";
			$app_action = mysqli_query( $dbc , $app_query );
			$fav_query = "SELECT Id FROM FavJobs WHERE JobId = '$fav_user_id'";
			$fav_action = mysqli_query( $dbc , $fav_query );
			//check if query is successful, if not return an error
			if(!$fav_action || !$app_action){
				$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc );
			}else{
				//if query is successful then check number of rows that match
				$num_check_fav = mysqli_num_rows( $fav_action );
				$num_check_app = mysqli_num_rows( $app_action );
			}
			$output_reqs = array();
			$output_reqs = explode( '<--break-->' , $job_data_inputs['RequirementArray'] );
			$num_of_req = count($output_reqs);
		}
	}

}

$page_title = "Profile";
include ('includes/header.php');
?>

<?php if(!empty($errors)) : ?>
	<div class="error_bar"><p>ERROR</p></div>
<?php endif; ?>

<div class="center_it">
	<div class="main_content" >

	<div class="profile_spacer"></div>

	<div class="profile_right">

		<?php 	
		if( !empty($job_data_inputs['DisplayImage']) ){
			$ProfileImageCopy = "background-image: url('" . $job_data_inputs['DisplayImage'] . "');" ;
			echo 	'<div class="photo_buff" style="' . $ProfileImageCopy . '">
					</div><!-- photo buff -->';
		}else{
			$ProfileImageCopy = "background-image: url('images/sunset.jpg');" ;
			echo 	'<div class="photo_buff" style="' . $ProfileImageCopy . '">
					</div><!-- photo buff -->';
		}
		?>

		<?php if( !empty($errors) ) : ?>
			<div class="right_text errors">
				<?php echo "<p>The following errors were found : </p>"; foreach( $errors as $msg ){ echo "<p>$msg</p>" ; } ?>
			</div><!-- right_text -->
		<?php endif; ?>

		<?php if( !empty($job_delete_success) ) : ?>
			<div class="right_text">
				<p><?php echo $job_delete_success ; ?></p>
			</div><!-- right_text -->
		<? endif; ?>

		<?php if($_POST['delete_job_no']) : ?>
			<div class="right_text">
				<p>Your delete request has been cancelled.</p>
			</div><!-- right_text -->
		<?php endif ; ?>

		<?php if( $_SERVER[ 'REQUEST_METHOD' ] != 'POST' ) : ?>

			<div class="right_text">
				<p>Welcome to the Jobsession Employer Profile</p><br>
				<p>You can now create Free Jobs. Just visit the "Post a Job for FREE" option on the side bar.</p><br>
				<p>In the Employer Profile you can edit any Jobs that you have already created. These jobs should
					appear on the left hand side of your profile under 'Your Jobs'.</a></p><br>
				<p>All application to your Jobs will be sent to your email. You will also be able to see the QuickCV's
					of all the applicants for each Job here.</p>
			</div><!-- right_text -->

		<?php endif; ?>

		<?php 
		if( !empty($_POST['wanted_code']) ){

			if( !$job_data_inputs['Active'] ){
				echo 	'<div class="right_text errors">
							<p>This Job is currently NOT active.</p>
						</div><!-- right_text -->';
			}else{
				echo 	'<div class="right_text">
							<h3>' . $job_data_inputs['JobName'] . '</h3><br>
							<p>This Job is currently active.</p>
							<p> - It has been favourited ' . $num_check_fav . ' times. </p>
							<p> - It has been applied for ' . $num_check_app . ' times. </p>
						</div><!-- right_text -->';
			}

			echo 	"<form action='job_submit_ini.php' method='POST'>
						<input type='hidden' name='q' value='" . $job_data_inputs['QueryCode'] . "'>
						<input type='submit' class='submit_form , submit_button' value='Edit'>
					</form>";

		}
		?>

		<?php include("includes/profile_apps.php"); ?>

	</div><!-- profile_right -->
	
	<div class="profile_left">
		<div class="photo_buff">
			<div class="person_details">
			<?php echo "<h1>Welcome " . $emp_array['FName'] . " " . $emp_array['LName'] . "</h1>" ; ?>
			<?php echo "<h2>Here are your details </h2>" ; ?>
			<?php echo "<p>Your username : " . $_SESSION['user'] . "</p>" ;?>
			<?php echo "<p>Email [Main] : " . $user_array['Email'] . "</p>" ; ?>
			<?php if( !empty($emp_array['EmailForApplications']) ){echo "<p>Email [For Applications] : " . $emp_array['EmailForApplications'] . "</p>" ;} ?>
			<?php echo "<p>Phone [Main] : " . $user_array['Phone'] . "</p>" ; ?>
			<?php if( !empty($emp_array['CompanyPhone']) ){echo "<p>Company Phone : " . $emp_array['CompanyPhone'] . "</p>" ;} ?>
			<?php echo "<p>Company Name : " . $emp_array['CompanyName'] . "</p>" ; ?>
			<?php echo "<p>Address : " . $user_array['Address1'] . "</p>" ; ?>
			<?php if( !empty($user_array['Address2']) ){echo "<p>Post Code : " . $user_array['Address2'] . "</p>" ;} ?>
			</div><!-- person_details -->
		</div><!-- photo buff -->
		
		<ul class="first_list">
			<?php if($_SESSION['log']) : ?>
			<a href="index.php">
				<li><p><span class="icon-house"></span>Home</p></li>
			</a>
			<a href="log_out.php">
				<li><p><span class="icon-logout"></span>Log out of <?php echo $_SESSION['user']; ?></p></li>
			</a>
			<a href="job_submit_ini.php">
				<li><p><span class="icon-plus2"></span>Post a Job for FREE</p></li>
			</a>
			<?php else : ?>
			<a href="log_in.php">
				<li><p><span class="icon-login"></span>Log in</p></li>
			</a>
			<a href="user_reg.php">
				<li><p><span class="icon-user-add"></span>Register new account</p></li>
			</a>
			<?php endif; ?>
			<a href="update_acc.php">
				<li><p><span class="icon-user"></span>Update my account info</p></li>
			</a>
			<li class="job_sub_green"><p style="color:white;">Your Jobs</p></li>
			<?php
			$current_user_id = $_SESSION['id'];
			$job_find_query = "SELECT * FROM JobSubmit WHERE UserId = '$current_user_id'";
			$apply_query = mysqli_query( $dbc , $job_find_query );
			if( !$apply_query ){
				echo '<li><p>There is a promblem with our server : ' . mysqli_error( $dbc ) . '</p></li>';
			}else{
				$numberJobs = 0 ;
				while( $row = mysqli_fetch_assoc( $apply_query ) ){
					if(!$row['Deleted']){
						unset($color);
						if( !$row['Active'] ){ $color = "#ffadad" ; }
						echo "<form action='employer_profile.php' method='POST'>
								<input type='hidden' value='" . $row['QueryCode'] . "' name='delete_job'>
								<input type='submit' value='Delete' class='profile_jobs_delete'>
							</form>
							<form action='employer_profile.php' method='POST'>
								<input type='hidden' value='" . $row['QueryCode'] . "' name='wanted_code'>
								<input type='submit' value='" . $row['JobName'] . "' class='profile_jobs' style='background-color: $color'>
							</form>";
					}
					$numberJobs ++ ;
				}
				if($numberJobs == 0){
					echo '<li><p>No Current Jobs</p></li>';
				}
			}
			?>
		</ul>
		
		<div class="main_photo">
			<?php if(!empty($emp_array['CompanyLogo'])){ $mainImageCopy = "background-image: url('" . $emp_array['CompanyLogo'] . "');" ; }else{ $mainImageCopy = "background-image: url('images/default_head.jpg');" ; } ?>
			<div class="main_photo_hold" style="<?php echo $mainImageCopy ; ?>"></div>
		</div><!-- main_photo -->

	</div><!-- profile_left -->
	
	</div><!-- main_content -->
</div><!-- center_it -->

<?php
include("includes/footer.php");
?>