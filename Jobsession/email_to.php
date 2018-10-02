<?php
session_start();
require('../../connect_db.php');
require('includes/first_declarations.php');
require('includes/register_functions.php');
require('includes/job_sub_functions.php');
require('includes/PHPMailer/PHPMailerAutoload.php');
include('includes/grab_all_data.php');

if( ($_SERVER[ 'REQUEST_METHOD' ] != 'GET') || (empty($_GET['return_job'])) ){ 
	header("Location: log_in.php");
	exit();
}else{
	//there are errors sending data to this page in the_job_query.php
	$job_id_here = $_GET['return_job'];
	$universal_query = "SELECT * FROM JobSubmit WHERE Active = '1' AND Id = '$job_id_here'";
	$universal_action = mysqli_query( $dbc , $universal_query );
	if( !$universal_action ){
		$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc );
	}else{
		$uni_data = mysqli_fetch_assoc( $universal_action );
		$output_reqs = array();
		$output_reqs = explode( '<--break-->' , $uni_data['RequirementArray'] );
		$num_of_req = count($output_reqs);
		$emp_user_id = $uni_data['UserId'];
		$get_emp_dp = "SELECT * FROM Employers WHERE UserId = '$emp_user_id'";
		$get_emp_dp_act = mysqli_query( $dbc , $get_emp_dp );
		if( $get_emp_dp_act ){
			$get_emp_dp_data = mysqli_fetch_assoc( $get_emp_dp_act );
			
			$rot = 0;
			while( $rot < $num_of_req ){
				$req_list_here .= "<li><p>" . $output_reqs["$rot"] . "</p></li>";
				$rot ++ ;
			}

			$email = new PHPMailer();
			$email->isHTML(true);
			$email->Subject   = "A Job brought to you by Jobsession - " . $uni_data['JobName'] ;
			$email->FromName   = 'Jobsession';
			$email->Body      = $email_des['header'] .
					'<p><u>Company</u> : ' . $get_emp_dp_data['CompanyName'] . '</p>' .
					'<p><u>Position created on</u> : ' . $uni_data['TimeOfSubmit'] . '</p>' .
					'<p><u>Location</u> : ' . $uni_data['Location'] . '</p>' .
					'<p><u>Address</u> : ' . $uni_data['JobAddress'] . '</p>' .
					'<p><u>Salary</u> : ' . $uni_data['Income'] . '</p>' .
					'<p><u>Category</u> : ' . $uni_data['Category'] . '</p>' .
					'<p><u>Position requirements</u> : </p>
					<ul>' . $req_list_here . '</ul>
					<p><u>Description</u> : </p>
					<p>' . $uni_data['Description'] . '</p>' .
					'<p><a href="http://jobsession.com.au/full_job_info.php?return_job=' . $uni_data['Id'] . '">Go to this Job.</a></p>' .
					$email_des['footer'] ;
			$email->AddAddress( $user_array['Email'] );
			if(!$email->send()) {
				$errors[] = "There is something wrong with our servers, your application was unable to be sent.";
			}else{
				header("Location: " . $_SESSION['url1'] . "&sent_email=true");
				exit();
			}
			
		}else{
			$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc );
		}
	}
}

$page_title = "Email Job";
include ('includes/header.php');
?>
		
<?php include('includes/log_in_bar.php'); ?>

<div class="center_it">
	<div class="main_content" >
	
	<?php include ('includes/sidebar.php'); ?>
	
		<div class="main_right">
		
			<div class="right_art">
				<div class="text">
					<p>The job details and information have been sent to your account email address</p>
				</div><!-- text -->
			</div><!-- right_art -->
		
			<?php $universal_query = "SELECT * FROM JobSubmit WHERE Active = '1' ORDER BY Id DESC"; ?>
			<?php include('includes/the_job_query.php'); ?>
						
		</div><!-- main_right -->
	</div><!-- main_content -->
</div><!-- center_it -->

<?php
include ('includes/footer.php');
?>