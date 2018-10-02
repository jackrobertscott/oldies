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

if ( $_SERVER[ 'REQUEST_METHOD' ] != 'POST' || !$_SESSION['log'] || $_SESSION['es'] != 2 ){
	
	header('Location: no_access.php');
	exit();

}else{

	$query_code = $_POST['q'];
	if( empty($query_code) ){
		header('Location: no_access.php');
		exit();
	}

	/* if(!empty($_POST['DisplayImage'])){} 
	if(!empty($_POST['PriorCat'])){} */

	$time_sec = time(); //time sec is used to check if job is outdated
	$update_active = "UPDATE JobSubmit SET 	Active = '1' ,
											TimeDifference = '$time_sec'
											WHERE QueryCode = '$query_code'";
											
	if(!mysqli_query($dbc , $update_active)){
		$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc );
		$errors[] = 'Please contact Jobsession for <a href="support.php">support.</a>';
	}

}

$page_title = "Process Job";
include('includes/header.php');
?>

<?php include('includes/log_in_bar.php'); ?>

<div class="center_it">
	<div class="main_content" >
	
	<?php include ('includes/sidebar.php'); ?>
	
		<div class="main_right">

			<?php if( (!empty($errors)) && ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') ) : ?>
			
				<div class="right_art errors">
					<div class="text">
		
					<?php
					echo "<p>The following things still need to be checked :</p>";
					foreach( $errors as $error ){ echo "<p>" . $error . "</p>" ; }
					?>
		
					</div><!-- text -->
				</div><!-- art_right -->
		
			<?php else : ?>

				<div class="right_art">
					<div class="text">
					
					<p>Your Job has been successfully submited.</p>
					<br>
					<p>All applications will be sent to your email account.</p>
					<br>
					<p>View and Update it in your <a href="employer_profile.php">Employer Profile</a></p>

					</div><!-- text -->
				</div><!-- right_art -->

			<?php endif; ?>

		</div><!-- main_right -->
	</div><!-- main_content -->
</div><!-- center_it -->

<?php 
include('includes/footer.php');
?>