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

if( ($_SERVER[ 'REQUEST_METHOD' ] != 'GET') || (empty($_GET['return_job'])) ){ 
	header("Location: log_in.php");
	exit();
}else{
	//get the job id requested to be shown on the page. GET over POST allows this page to be bookmarked
	$job_id_here = $_GET['return_job'];
	//retrieve the data from the Job
	$universal_query = "SELECT * FROM JobSubmit WHERE Id = '$job_id_here'";
	$universal_action = mysqli_query( $dbc , $universal_query );
	if( !$universal_action ){
		$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc );
	}else{
		$uni_data = mysqli_fetch_assoc( $universal_action );
		if($uni_data['Deleted']){
			header('Location: no_access.php');
			exit();
		}
		//An array is created to store the Job requirements that are exploded from the RequirementArray
		$output_reqs = array();
		$output_reqs = explode( '<--break-->' , $uni_data['RequirementArray'] );
		//Count the number of requirements, needed to interate over the length of the requirements when echoing them in the code bellow
		$num_of_req = count($output_reqs);
		$emp_user_id = $uni_data['UserId'];
		//get the job employer's employer table data
		$get_emp_dp = "SELECT * FROM Employers WHERE UserId = '$emp_user_id'";
		$get_emp_dp_act = mysqli_query( $dbc , $get_emp_dp );
		//get the job employer's user table data
		$get_emp_dp_user = "SELECT * FROM Users WHERE Id = '$emp_user_id'";
		$get_emp_dp_act_user = mysqli_query( $dbc , $get_emp_dp_user );
		//check if the applicant user has already applied
		$check_if_applied_query = "SELECT * FROM AppliedJobs WHERE JobId = '$job_id_here' AND UserId = '$id'";
		$check_if_applied_action = mysqli_query( $dbc , $check_if_applied_query );
		$fav_query = "SELECT Id FROM FavJobs WHERE JobId = '$job_id_here' AND UserId = '$id'";
		$fav_action = mysqli_query( $dbc , $fav_query );
		$fav_status = false;
		//check if query is successful, if not return an error
		if(!$fav_action){
			$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc );
		}else{
			//if query is successful then check number of rows that match
			//if matching rows > 0 then the user has already favourited this job
			$num_check_fav = mysqli_num_rows( $fav_action );
			if($num_check_fav > 0){
				$fav_status = true;
			}
		}
		if( !$get_emp_dp_act || !$get_emp_dp_act_user || !$check_if_applied_action ){
			$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc );
		}else{
			$get_emp_dp_data = mysqli_fetch_assoc( $get_emp_dp_act );
			$get_emp_dp_data_user = mysqli_fetch_assoc( $get_emp_dp_act_user );
			$num_check_applied = mysqli_num_rows( $check_if_applied_action );
			if($num_check_applied > 0){
				$applied_already = true;
			}
		}
	}
}

if(isset($uni_data['JobName'])){
	$page_title = $uni_data['JobName'];
}else{
	$page_title = "Job Details";
}
include ('includes/header.php');
?>
		


<?php include('includes/log_in_bar.php'); ?>

<div class="center_it">
	<div class="main_content" >
	
	<?php include ('includes/sidebar.php'); ?>
	
		<div class="main_right">
		
			<?php if( !empty($errors) ) : ?>
			
			<div class="right_art errors">
				<div class="text">
					<?php foreach( $errors as $err ){ echo "<p>$err</p>" ; } ?>
				</div><!-- text -->
			</div><!-- right_art -->
				
			<?php else : ?>

				<?php if($_GET['sent_email'] == true) : ?>

				<div class="right_art">
					<div class="text">
						<p>An email with this Jobs info has been successfully sent to <?php echo $user_array['Email']; ?></p>
					</div><!-- text -->
				</div><!-- right_art -->

				<?php endif; ?>

				<?php if(!$_SESSION['log']) : ?>

				<div class="right_art">
					<div class="text">
						<p>To APPLY for this Job, <a href="log_in.php">Log In</a> or <a href="user_reg.php">Sign Up</a></p>
					</div><!-- text -->
				</div><!-- right_art -->

				<?php elseif($applied_already) : ?>

				<div class="right_art">
					<div class="text">
						<p>You have Already applied for this Job. See it here at <a href="applied_jobs.php">Jobs Applied For.</a></p>
					</div><!-- text -->
				</div><!-- right_art -->

				<?php elseif(!$uni_data['Active']) : ?>

				<div class="right_art errors">
					<div class="text">
						<p>This Job is currently not Active and can not be Applied For.</p>
					</div><!-- text -->
				</div><!-- right_art -->

				<?php elseif($_SESSION['ver'] != 2) : ?>

				<div class="right_art errors">
					<div class="text">
						<p>You must <a href="verify_account.php">VERIFY</a> your account before you can apply for this job.</p>
					</div><!-- text -->
				</div><!-- right_art -->

				<?php elseif($_SESSION['es'] != 2) : ?>

					<?php $can_apply = true; ?>

				<?php endif ; ?>

			<div class="right_art">
				<div class="updating_data_info">
					<p><?php echo $uni_data['JobName'] ; ?></p>
				</div><!-- updating_data_info -->
				<?php if( $uni_data['DisplayImage'] ) : ?>
					<?php $DisplayImageCopy = "background-image: url(" . $uni_data['DisplayImage'] . ")" ; ?>
					<div class="image_hold_page" style="<?php echo $DisplayImageCopy ; ?>">
					</div><!-- image_hold_page -->
				<?php endif ; ?>
				<div class="text">
					<div class="top_info">
						<div class="info_left">
							<?php if( $uni_data['TimeOfSubmit'] ) : ?><p>Position created on : <?php echo $uni_data['TimeOfSubmit'] ; ?></p>
							<?php endif ; ?>
							<?php if( $get_emp_dp_data['CompanyName'] ) : ?><p>Company : <?php echo $get_emp_dp_data['CompanyName'] ; ?></p>
							<?php endif ; ?>
							<?php if( $get_emp_dp_data['CompanyURL'] ) : ?><p>Website : <?php echo $get_emp_dp_data['CompanyURL'] ; ?></p><br>
							<?php endif ; ?>
						</div>
						<?php if($uni_data['Active']) : ?>
						<div class="info_right">
							<form action="<?php if( $_SESSION['log'] ){ echo "email_to.php" ; }else{ echo "log_in.php" ; } ?>" method="GET" class="thepreviewjob" id="emails<?php echo $job_id_here ; ?>">
								<input type="hidden" name="return_job" value="<?php echo $job_id_here ; ?>">
								<input type="submit" class="email" value="<?php if($_GET['sent_email'] == true){ echo 'RESEND EMAIL'; }else{ echo 'EMAIL ME THIS JOB'; } ?>" title="Send this job's details to your email account">
							</form>
							<form class="thepreviewjob favourite">
								<input type="hidden" name="fav_job" value="<?php echo $job_id_here ; ?>">
								<input type="hidden" name="user_id" value="<?php if(!empty($id)){ echo $id ; }else{ echo "0"; } ?>">
								<input type="submit" class="fav_apply fav_ajax<?php if($fav_status){ echo " fav_true"; } ?>" title="Favourite this Job" value="">
							</form>
						</div>
						<?php endif ; ?>
					</div>
					<?php if( $uni_data['Location'] ) : ?><p>Location : <?php echo $uni_data['Location'] ; ?></p>
					<?php endif ; ?>
					<?php if( $uni_data['JobAddress'] ) : ?><p>Address : <?php echo $uni_data['JobAddress'] ; ?></p>
					<?php endif ; ?>
					<?php if( $uni_data['Income'] ) : ?><p>Salary : <?php echo $uni_data['Income'] ; ?></p>
					<?php endif ; ?>
					<?php if( $uni_data['Category'] ) : ?><p>Category : <?php echo $uni_data['Category'] ; ?></p>
					<?php endif ; ?>
					<?php if( $uni_data['TypeTime'] ) : ?><p>Type of Work : <?php echo $uni_data['TypeTime'] ; ?></p><br>
					<?php endif ; ?>
					<p>Position requirements : </p>
					<ul>
					<?php
					$rot = 0;
					while( $rot < $num_of_req ){
						echo "<li><p>" . $output_reqs["$rot"] . "</p></li>";
						$rot ++ ;
					}
					?>
					</ul>
					<br>
					<p>Description : </p>
					<p><?php echo nl2br($uni_data['Description']); ?></p>
				</div><!-- text -->
			</div><!-- right_art -->

				<?php if($can_apply == true) : ?>
					<div class="upd_inf">
						<form action="apply.php" method="POST">
							<input type="hidden" name="apply_true" value="<?php echo $job_id_here ; ?>">
							<input type="hidden" name="emp_id" value="<?php echo $emp_user_id ; ?>">
							<input type="hidden" name="direction" value="<?php if( !empty($get_emp_dp_data['EmailForApplications']) ){ echo $get_emp_dp_data['EmailForApplications'] ; }else{ echo  $get_emp_dp_data_user['Email'] ; } ?>">
							<input type="submit" class="submit_inf , submit_button" value="Apply">
						</form>
					</div><!-- upd_inf -->
				<?php endif ; ?>
						
			<?php endif ; ?>
			
		</div><!-- main_right -->
	</div><!-- main_content -->
</div><!-- center_it -->

<?php
include ('includes/footer.php');
?>