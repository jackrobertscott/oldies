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

$num_reqs = 3;
	
if($_SERVER[ 'REQUEST_METHOD' ] == 'POST'){

	$direct_next = $_POST['direct_next'];
	$query_code = $_POST['q'];
	$dont_submit_db = $_POST['dont_submit_db'];

	if( empty($query_code) ){
		header('Location: no_access.php');
		exit();
	}

	if( isset($_POST['pause_field']) ){ $num_reqs = $_POST['pause_field']; }

	if( !empty($_POST['add_field']) ){
		$add_field_tmp = $_POST['add_field'];
		$num_reqs = ( $add_field_tmp + 1 );
		$_POST['add_field'] = NULL;
	}elseif( !empty($_POST['remove_field']) ){
		$add_field_tmp = $_POST['remove_field'];
		if($add_field_tmp > 2){$num_reqs = ( $add_field_tmp - 1 );}else{$num_reqs = 2;}
		$_POST['remove_field'] = NULL;
	}
	
	$rot = 0 ;
	$check_error = 0;
	$requirement_array = array();
	
	while( $rot < $num_reqs ){
		$tmp_value = "req" . "$rot" ;
		$num = ( $rot + 1 ) ;
		if( empty( $_POST["$tmp_value"] ) ){
			$check_error ++ ;
		} else {
			$per_value = mysqli_real_escape_string( $dbc , trim($_POST["$tmp_value"]) );
			$per_value = strip_tags($per_value);
			$requirement_array[] = $per_value ;
		}
		$rot ++ ;
	}
	
	if( $check_error > ($num_reqs - 2) && isset($_POST['pause_field']) ){
		$errors[] = "You have not filled in all requirement boxes.";
		$errors[] = "There must be a minimum of 2 requirements.";
	}else{
		$insert_reqs = implode( '<--break-->' , $requirement_array );
	}
	
	if( empty($errors) && !$dont_submit_db && $_POST['reload_it'] ){
		$message = submit_job_req( $dbc , $insert_reqs , $query_code );
		if(!empty($message)){
			$errors[] = $message;
		}else{
			$clear_repeat = true ;
		}
	}
	
	$universal_query = "SELECT * FROM JobSubmit WHERE QueryCode = '$query_code'";
	$universal_action = mysqli_query( $dbc , $universal_query );

	if( $universal_action ){
		$job_data_inputs = mysqli_fetch_assoc( $universal_action );
		if( !empty($job_data_inputs['RequirementArray']) ){
			$output_reqs = array();
			$output_reqs = explode( '<--break-->' , $job_data_inputs['RequirementArray'] );
			$num_of_req = count($output_reqs);
			if( empty($errors) && $_POST['feild_alter'] != true ){ $num_reqs = $num_of_req; }
		}
	} else {
		$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc );
	}

}else{
	header('Location: no_access.php');
	exit();
}

$page_title = "Job Creation";
include("includes/header.php");
?>

<?php include('includes/log_in_bar.php'); ?>

<div class="center_it">
	<div class="main_content" >
	
	<?php include ('includes/sidebar.php'); ?>
	
		<div class="main_right">
		
		<?php if( (!empty($errors)) && ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') && !$dont_submit_db ) : ?>
	
			<div class="right_art errors">
				<div class="text">
	
				<?php
				echo "<p>The following things still need to be checked :</p>";
				foreach( $errors as $error ){ echo "<p>" . $error . "</p>" ; }
				?>
	
				</div><!-- text -->
			</div><!-- art_right -->
			
		<?php endif; ?>
		
		<?php if( $direct_next ) : ?>
		
			<div class="right_art">
				<div class="text">
					<p>Loading...</p>
				</div><!-- text -->
			</div><!-- art_right -->
			
			<form method="POST" id="frm1" action="job_submit_dec.php">
				<input type="hidden" name="q" value="<?php echo $query_code ; ?>">
				<input type="hidden" name="dont_submit_db" value="true">
			</form>
			
			<script type="text/javascript">
				$(document).ready(function(){
					 $('#frm1').submit();
				});
			</script>
	
		<?php else : ?>
	
			<?php if( $_SESSION['es'] == 2 ) : ?>
	
				<div class="upd_inf">
					<?php if( !empty( $query_code ) ) : ?>
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
					<?php endif; ?>
					<div class="updating_data_info">
							<p>*Hit 'Save' before you add or remove any new requirement feilds.</p>
					</div><!-- updating_data_info -->
					<div class="remove_hold" style="width:96px;">
						<form action="job_submit_req.php" method="POST">
							<?php
							$rot = 0;
							while( $rot < $num_reqs ){
								$req_value = NULL;
								$tmp_value = "req" . "$rot" ;
								if( !empty($_POST["$tmp_value"]) ){ $req_value = $_POST["$tmp_value"]; }
								echo	'<input type="hidden" name="req' . $rot . '" value="' . $req_value . '">';
								$rot ++ ;
							}
							?>
							<input type="hidden" name="q" value="<?php echo $query_code ; ?>">
							<input type="hidden" name="dont_submit_db" value="true">
							<input type="hidden" name="feild_alter" value="true">
							<input type="hidden" name="add_field" value="<?php echo $num_reqs; ?>">
							<input class="remove_data" type="submit" value="Add Field" style="width:86px;">
						</form>
						<form action="job_submit_req.php" method="POST">
							<?php
							$rot = 0;
							while( $rot < $num_reqs ){
								$req_value = NULL;
								$tmp_value = "req" . "$rot" ;
								if( !empty($_POST["$tmp_value"]) ){ $req_value = $_POST["$tmp_value"]; }
								echo	'<input type="hidden" name="req' . $rot . '" value="' . $req_value . '">';
								$rot ++ ;
							}
							?>
							<input type="hidden" name="q" value="<?php echo $query_code ; ?>">
							<input type="hidden" name="dont_submit_db" value="true">
							<input type="hidden" name="feild_alter" value="true">
							<input type="hidden" name="remove_field" value="<?php echo $num_reqs; ?>">
							<input class="remove_data" type="submit" value="Remove" style="width:86px;">
						</form>
					</div><!-- remove_hold -->
					<form action="job_submit_req.php" method="POST" id="job_sub_form">
						<input type="hidden" name="reload_it" value="true">
						<input type="hidden" name="q" value="<?php echo $query_code ; ?>">
						<input type="hidden" name="pause_field" value="<?php echo $num_reqs; ?>">
						
						<?php
						$rot = 0;
						while( $rot < $num_reqs ){
							$req_value = NULL;
							$tmp_value = "req" . "$rot" ;
							if( !empty($_POST["$tmp_value"]) ){ $req_value = $_POST["$tmp_value"]; }elseif( !empty($output_reqs) && empty($errors) ){ $req_value = $output_reqs[$rot]; }
							if( $rot < 2 ){
							echo 	'<div class="half_box_long">
										<p>Insert a requirement : </p>
									</div>
									<input class="inf" placeholder="Requirement" type="text" name="req' . $rot . '" value="' . $req_value . '">';
							}else{
							echo 	'<div class="half_box_long">
										<p>Insert a requirement : </p>
									</div>
									<input class="inf_long" placeholder="Requirement" type="text" name="req' . $rot . '" value="' . $req_value . '">';
							}

							$rot ++ ;
						}
						?>
						
						<input type="submit" class="submit_inf , submit_button" value="Save" style="background-color: #46c2d7;">
					</form>
				</div><!-- upd_inf -->

			<?php endif; ?>	

			<?php if( $clear_repeat ) : ?>

				<div class="upd_inf">
					<form action="job_submit_dec.php" method="POST">
						<input type="hidden" name="direct_next" value="true">
						<input type="hidden" name="q" value="<?php echo $query_code ; ?>">
						<input type="submit" class="submit_inf , submit_button" value="Continue">
					</form>
				</div><!-- upd_inf -->

			<?php endif ; ?>
		
		<?php endif; ?>
		
		</div><!-- main_right -->
	</div><!-- main_content -->
</div><!-- center_it -->

<?php
include("includes/footer.php");
?>