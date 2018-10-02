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

//If user does not have a quick CV they are redirected to set up one
if( !$user_array['QuickCV'] ){
	header('Location: quick_cv.php');
	exit();
//If the page has been accesssed with the correct post method, it will continue. If not it will be directed
}elseif( !empty($_POST['apply_true']) ){
	//Check that there is a direction to send email application
	if( empty($_POST['direction']) ){
		$errors[] = "There is something wrong with the job. We appologise for this inconvenience.";
	}else{
		//check that the application made does not allready exist. (should return with 0 results from mysqli_num_results)
		$job_id_here = $_POST['apply_true'];
		$check_if_applied_query = "SELECT Id FROM AppliedJobs WHERE JobId = '$job_id_here' AND UserId = '$id'";
		$check_if_applied_action = mysqli_query( $dbc , $check_if_applied_query );
		if(!$check_if_applied_action){
			$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc );
		}else{
			$num_check_applied = mysqli_num_rows( $check_if_applied_action );
			if($num_check_applied > 0){
				$applied_already = true;
			}else{
				$direction = $_POST['direction'];
				$emp_id = $_POST['emp_id'];
				$message = apply_for_job( $dbc , $job_id_here , $direction , $emp_id , $user_array['Email']);
				if(!empty($message)){
					$errors[] = $message;
				}
			}
		}
	}
}else{
	header('Location: no_access.php');
	exit();
}

if( !$_SESSION['log'] || $_SERVER[ 'REQUEST_METHOD' ] != 'POST' ){
	header('Location: no_access.php');
	exit();
}

$page_title = "Log In";
include('includes/header.php');
?>

<?php include('includes/log_in_bar.php'); ?>

<div class="center_it">
	<div class="main_content" >
	
	<?php include ('includes/sidebar.php'); ?>
		<div class="main_right">

		<?php if($applied_already) : ?>

			<div class="right_art">
				<div class="text">
					<p>You have already applied for this job.</p>
				</div><!-- text -->
			</div><!-- art_right -->
				
		<?php elseif(!empty($errors) ) : ?>

			<div class="right_art errors">
				<div class="text">
					<p>Please excuse us, we are having some technical difficulties.</p>
					<?php foreach( $errors as $err ){ echo "<p>$err</p>" ; } ?>
				</div><!-- text -->
			</div><!-- right_art -->

		<?php else : ?>

			<div class="right_art">
				<div class="text">
					<p>You have successfully applied for the job</p>
					<!-- can add the jobs name into the text above -->
				</div><!-- text -->
			</div><!-- art_right -->

		<? endif; ?>

		</div><!-- main_right -->
	</div><!-- main_content -->
</div><!-- center_it -->

<?php
include('includes/footer.php');
?>