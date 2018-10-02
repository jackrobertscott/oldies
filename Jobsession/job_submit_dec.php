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
	
if( $_SESSION['es'] != 2 ){
	header('Location: no_access.php');
	exit();
}

if($_SERVER[ 'REQUEST_METHOD' ] == 'POST'){

	$query_code = $_POST['q'];
	$dont_submit_db = $_POST['dont_submit_db'];

	if( empty($query_code) ){
		header('Location: no_access.php');
		exit();
	}

	if( $_POST['remove_image'] == true ){

		$display_query = "UPDATE JobSubmit SET DisplayImage = '$tmp_image_dir_name' WHERE QueryCode = '$query_code'";
		$insert_display = mysqli_query ( $dbc , $display_query ) ;
		if( !$insert_display ){
			$message = 'Error! : ' . mysqli_error( $dbc ) ;
		}

	}else{
		
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
			
		//must make $query_code the job code from previous step job_submit_ini
		
		if( empty($errors) && !$dont_submit_db && $_POST['reload_it'] ){
			list($message , $tmp_image_dir_name) = submit_job_dec( $dbc , $image_tmp , $image_ext , $query_code );
			//$tmp_image_dir_name is not actually nessesary any more, but im too lazy to remove it.
			if(!empty($message)){
				$errors[] = $message;
			}else{
				$clear_repeat = true ;
			}
		}

	}
	
}else{
	header('Location: no_access.php');
	exit();
}

//The sneak previews must be made through accessing the information off the Database
//so should pretty much be able to get the code straight off the job querys file

$universal_query = "SELECT * FROM JobSubmit WHERE QueryCode = '$query_code'";
$universal_action = mysqli_query( $dbc , $universal_query );

if( !$universal_action ){
	$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc );
	$dont_show_data = true;
}else{
	$uni_data = mysqli_fetch_assoc( $universal_action );
	$output_reqs = array();
	$output_reqs = explode( '<--break-->' , $uni_data['RequirementArray'] );
	$num_of_req = count($output_reqs);
	$emp_user_id = $uni_data['UserId'];
	$desc_shortened = substr( $uni_data['Description'] , 0 , 150 ) . '...';
	$get_emp_dp = "SELECT * FROM Employers WHERE UserId = '$emp_user_id'";
	$get_emp_dp_act = mysqli_query( $dbc , $get_emp_dp );
	if( $get_emp_dp_act ){
		$get_emp_dp_data = mysqli_fetch_assoc( $get_emp_dp_act );
	}else{
		$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc );
		$dont_show_data = true; //good as stop repetition in the job errors
	}
}

$page_title = "Job Creation";
include("includes/header.php");
?>
	
<?php include('includes/log_in_bar.php'); ?>

<div class="center_it">
	<div class="main_content" >
	
	<?php include ('includes/sidebar.php'); ?>
	
		<div class="main_right">
		
			<?php if( !empty($query_code) && $_SESSION['es'] == 2 ) : ?>
			
				<?php if($_SERVER[ 'REQUEST_METHOD' ] == 'POST') : ?>
	
					<?php if( !empty($errors) ) : ?>
						<div class="right_art errors">
							<div class="text">
								<p>The following errors have been found</p>
								<?php foreach( $errors as $err ){ echo "<p>$err</p>" ; } ?>
							</div><!-- text -->
						</div><!-- right_art -->
					<?php endif; ?>

					<?php if(!empty($uni_data['DisplayImage']) || !$uni_data['Active']) : ?>

						<div class="upd_inf">
							<form action="job_submit_ini.php" method="POST">
								<input type="hidden" name="q" value="<?php echo $query_code ; ?>">
								<input type="hidden" name="dont_submit_db" value="true">
								<input type="submit" class="job_steps" value="Details">
							</form>
							<form action="job_submit_req.php" method="POST">
								<input type="hidden" name="q" value="<?php echo $query_code ; ?>">
								<input type="hidden" name="dont_submit_db" value="true">
								<input type="submit" class="job_steps" value="Requirements">
							</form>
							<form action="job_submit_dec.php" method="POST">
								<input type="hidden" name="q" value="<?php echo $query_code ; ?>">
								<input type="hidden" name="dont_submit_db" value="true">
								<input type="submit" class="job_steps" value="Preview">
							</form>
							<form enctype="multipart/form-data" action="job_submit_dec.php" method="POST" id="job_sub_form">
								<input type="hidden" name="q" value="<?php echo $query_code ; ?>">
								<div class="updating_data_info">
									<p>ADD A BANNER IMAGE<br><br>The Image must be .jpeg .jpg or .png [ width : height , 4.2 : 1 ]</p>
								</div><!-- updating_data_info -->
								<div class="half_box_long">
									<p>Upload a Banner Image : </p>
								</div>
								<input class="inf_long" type="file" name="file_sub" value="Image file">
								<input type="hidden" name="reload_it" value="true">
								<input type="submit" class="submit_inf , submit_button" value="Save Uploaded Image">
							</form>
						</div><!-- upd_inf -->

					<?php else : ?>

						<div class="upd_inf">
							<form action="job_submit_ini.php" method="POST">
								<input type="hidden" name="q" value="<?php echo $query_code ; ?>">
								<input type="hidden" name="dont_submit_db" value="true">
								<input type="submit" class="job_steps" value="Details">
							</form>
							<form action="job_submit_req.php" method="POST">
								<input type="hidden" name="q" value="<?php echo $query_code ; ?>">
								<input type="hidden" name="dont_submit_db" value="true">
								<input type="submit" class="job_steps" value="Requirements">
							</form>
							<form action="job_submit_dec.php" method="POST">
								<input type="hidden" name="q" value="<?php echo $query_code ; ?>">
								<input type="hidden" name="dont_submit_db" value="true">
								<input type="submit" class="job_steps" value="Preview">
							</form>
						</div><!-- upd_inf -->
						
					<?php endif ; ?>

					<div class="right_art">
						<div class="text">
							<p>Jobs, once activated, will last for 30 days before they are made inactive.</p>
						</div><!-- text -->
					</div><!-- right_art -->
	
					<?php if( !empty($uni_data['DisplayImage']) && $dont_show_data != true ) : ?>
		
						<div class="right_art">
							<?php $DisplayImageCopy = "background-image: url(" . $uni_data['DisplayImage'] . ");" ; ?>
							<div class="image_hold" style="<?php echo $DisplayImageCopy ; ?>">
							</div><!-- image_hold -->
							<div class="data">
								<div class="display_image_up"><!-- remember the up vs down -->
									<?php if( $get_emp_dp_data['CompanyThumb'] ){ $the_logo_ofc = "background-image: url(" . $get_emp_dp_data['CompanyThumb'] . ");" ; }else{ $the_logo_ofc = "background-image: url(" . 'images/default_head.jpg' . ");" ; } ?>
									<div class="ofh" style="<?php echo $the_logo_ofc ; ?>">
									</div><!-- ofh -->
								</div><!-- display_image_up -->
								<input type="submit" class="apply_for" title="Favourite this Job" value="">
								<form class="thepreviewjob">
									<div class="go_to_exp">
										<h1 class="icon-export" title="More Info"></h1>
									</div>
								</form>
								<div class="text_title">
									<div class="main">
										<h1><?php echo $uni_data['JobName'] ; ?></h1>
									</div>
									<h2><?php echo $uni_data['TimeOfSubmit'] ; ?></h2>
								</div><!-- text_title -->
							</div><!-- data -->
							<div class="text_dev">
								<div class="hold_written">
									<p><?php echo $desc_shortened ; ?></p>
								</div><!-- hold_written -->
								<div class="hold_reqs">
									<ul>
										<?php
										$rot = 0;
										while( $rot < $num_of_req ){
											echo "<li><p>" . $output_reqs["$rot"] . "</p></li>";
											$rot ++ ;
										}
										?>
									</ul>
								</div><!-- hold_reqs -->
							</div><!-- text_dev -->
						</div><!-- right_art -->
	
					<?php else : ?>
		
						<div class="right_art">
							<div class="data">
								<div class="display_image_down"><!-- remember the up vs down -->
									<?php if( $get_emp_dp_data['CompanyThumb'] ){ $the_logo_ofc = "background-image: url(" . $get_emp_dp_data['CompanyThumb'] . ");" ; }else{ $the_logo_ofc = "background-image: url(" . 'images/default_head.jpg' . ");" ; } ?>
									<div class="ofh" style="<?php echo $the_logo_ofc ; ?>">
									</div><!-- ofh -->
								</div><!-- display_image_down -->
								<input type="submit" class="apply_for" title="Favourite this Job" value="">
								<form class="thepreviewjob">
									<div class="go_to_exp">
										<h1 class="icon-export" title="More Info"></h1>
									</div>
								</form>
								<div class="text_title">
									<div class="main">
										<h1><?php echo $uni_data['JobName'] ; ?></h1>
									</div>
									<h2><?php echo $uni_data['TimeOfSubmit'] ; ?></h2>
								</div><!-- text_title -->
							</div><!-- data -->
							<div class="text_dev_small">
								<div class="hold_written_small">
									<p><?php echo $desc_shortened ; ?></p>
								</div><!-- hold_written -->
								<div class="hold_reqs_small">
									<ul>
										<?php
										$rot = 0;
										while( $rot < $num_of_req ){
											echo "<li><p>" . $output_reqs["$rot"] . "</p></li>";
											$rot ++ ;
										}
										?>
									</ul>
								</div><!-- hold_reqs -->
							</div><!-- text_dev -->
						</div><!-- right_art -->
	
					<?php endif ; ?>

					<?php if( !empty($uni_data['DisplayImage']) && $dont_show_data != true && !$uni_data['Active']) : ?>

						<div class="upd_inf">
							<form action="job_submit_dec.php" method="POST">
								<input type="hidden" name="remove_image" value="true">
								<input type="hidden" name="q" value="<?php echo $query_code ; ?>">
								<input type="submit" class="submit_inf , submit_button" value="Remove Banner Image">
							</form>
						</div><!-- upd_inf -->

					<?php endif ; ?>

					<?php if( !$uni_data['Active'] && !empty($uni_data['RequirementArray'])) : ?>

						
						<?php if(!empty($uni_data['DisplayImage'])) : ?>

						<div class="upd_inf">
							<form action="process_job.php" method="POST">
								<input type="hidden" name="activate_job" value="true">
								<input type="hidden" name="q" value="<?php echo $query_code ; ?>">
								<div class="pur_inf"><p>The Job - Active for 30 days</p><p style="float: right;">FREE</p></div><div class="pur_tick"><p class="icon-checkmark"></p></div>
								<div class="updating_data_info" style="margin-bottom: 0;">
									<p>EXTRAS</p>
								</div><!-- updating_data_info -->
								<div class="pur_inf"><p>Include Banner Image [Upload Above]</p><p style="float: right;"></p></div>
								<div class="pur_tick"><p class="icon-checkmark"></p></div>
								<input type="hidden" name="DisplayImage" value="true">
								<input type="hidden" name="PriorCat" value="false">
								<input type="submit" class="submit_inf , submit_button" value="Finish and Activate">
							</form>
						</div><!-- upd_inf -->

						<?php else : ?>

						<div class="upd_inf">
							<form action="process_job.php" method="POST">
								<input type="hidden" name="activate_job" value="true">
								<input type="hidden" name="q" value="<?php echo $query_code ; ?>">
								<div class="pur_inf"><p>The Job - Active for 30 days</p><p style="float: right;">FREE</p></div><div class="pur_tick"><p class="icon-checkmark"></p></div>
								<div class="updating_data_info" style="margin-bottom: 0;">
									<p>EXTRAS</p>
								</div><!-- updating_data_info -->
								<div class="pur_inf"><p>Include Banner Image [Upload Above]</p><p style="float: right;"></p></div>
								<div class="pur_tick"><p class="icon-cross"></p></div>
								<input type="submit" class="submit_inf , submit_button" value="Finish and Activate">
							</form>
						</div><!-- upd_inf -->

						<?php endif ; ?>

					<?php endif ; ?>

				<?php endif ; ?>
		
			<?php endif ; ?>
		
		</div><!-- main_right -->
	</div><!-- main_content -->
</div><!-- center_it -->

<?php
include("includes/footer.php");
?>