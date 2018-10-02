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

$page_title = "Favourited Jobs";
include('includes/header.php');
?>

<?php include('includes/log_in_bar.php'); ?>

<div class="center_it">
	<div class="main_content" >
	<?php include ('includes/sidebar.php'); ?>
		<div class="main_right">
		
			<?php
			$jobs_applied_query = "SELECT * FROM FavJobs WHERE UserId = '$id'";
			$jobs_applied_action = mysqli_query( $dbc , $jobs_applied_query );
			if(!$jobs_applied_action){
				$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc );
			}else{
				$num_check_applied = mysqli_num_rows($jobs_applied_action);
				if($num_check_applied !== 0){
				
					echo '<div class="right_art">
							<div class="text">
								<p>You have favourited the following Jobs</p>
							</div><!-- text -->
						</div><!-- right_art -->';

					while($job_current = mysqli_fetch_assoc($jobs_applied_action)){

						$current_job = $job_current['JobId'];
						$select_the_job = "SELECT * FROM JobSubmit WHERE Id = '$current_job'";
						$action_job = mysqli_query( $dbc , $select_the_job );
						if( !$action_job ){
							$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc );
						}else{
							$action_job = mysqli_fetch_assoc( $action_job );
							$the_employer = $action_job['UserId'];
							$get_emp_dp = "SELECT CompanyName, CompanyLogo FROM Employers WHERE UserId = '$the_employer'";
							$get_emp_dp_act = mysqli_query( $dbc , $get_emp_dp );
							if( !$get_emp_dp_act ){
								$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc );
							}else{
								$get_emp_dp_act = mysqli_fetch_assoc( $get_emp_dp_act );
								if(!empty($get_emp_dp_act['CompanyLogo'])){
									$back_img = "url('" . $get_emp_dp_act['CompanyLogo'] . "')";
								}else{
									$back_img = "url('images/default_head.jpg')";
								}

								echo '<div class="right_art">
										<div class="text">
											<div class="app_photo" style="background-image:' . $back_img . ';"></div>
											<h1 class="app_title">' . $action_job['JobName'];
								if(!$action_job['Deleted']){
									echo ' <a href="full_job_info.php?return_job=' . $action_job['Id'] . '"><span class="icon-export"></span></a>';
								}else{
									echo ' [Not Available]';
								}
								echo '</h1>
											<p style="padding-top: 2px;">By <span style="font-weight:bold;">' . $get_emp_dp_act['CompanyName'] . '</span></p>' .
											'<p>Location : ' . $action_job['JobAddress'] . '</p>' .
											'<p>Salary : ' . $action_job['Income'] . '</p>' .
											'<p>Category : ' . $action_job['Category'] . '</p>' .
										'</div><!-- text -->
									</div><!-- right_art -->';

							}
						}

					}//end of while loop

				}else{
					$empty_jobs = true;
				}
			}
			?>

			<?php if( !empty($errors) ) : ?>
			
			<div class="right_art errors">
				<div class="text">
					<?php foreach( $errors as $err ){ echo "<p>$err</p>" ; } ?>
				</div><!-- text -->
			</div><!-- right_art -->
				
			<?php elseif($empty_jobs) : ?>

			<div class="right_art">
				<div class="text">
					<p>You have not favourited any jobs yet.</p>
				</div><!-- text -->
			</div><!-- right_art -->

			<?php endif ; ?>
			
		</div><!-- main_right -->
	</div><!-- main_content -->
</div><!-- center_it -->

<?php
include ('includes/footer.php');
?>