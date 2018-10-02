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
	
if(!$_SESSION['log']){
	header('Location: emp_user_reg.php?emp_yes=true');
	exit();
}elseif($_SESSION['ver'] != 2){
	header('Location: verify_account.php?emp_yes=true');
	exit();
}elseif($_SESSION['es'] != 2){
	header('Location: employer_reg.php?emp_yes=true');
	exit();
}

if($_SERVER[ 'REQUEST_METHOD' ] == 'POST'){

	$query_code = $_POST['q'];
	$dont_submit_db = $_POST['dont_submit_db'];
	$the_job = array();
	
	if( !empty($query_code) ){
	$this_job_query = "SELECT * FROM JobSubmit WHERE QueryCode = '$query_code'";
	$this_job_action = mysqli_query( $dbc , $this_job_query );

		if( $this_job_action ){
			$uni_data = mysqli_fetch_assoc( $this_job_action );
			$db_title = $uni_data['JobName'];
			$db_desc = $uni_data['Description'];
			$db_address = $uni_data['JobAddress'];
			$db_location = $uni_data['Location'];
			$db_income = $uni_data['Income'];
			$db_type = $uni_data['TypeTime'];
			$db_cat = $uni_data['Category'];
			$db_ne = $uni_data['NoEmail'];
		} else {
			$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc );
		}
	
	}
	
	if( empty( $_POST['title'] ) ){
		if( empty( $db_title ) ){
			$errors[] = 'Your Title input box is empty';
		}else{
			$title = $db_title ;
		}
	} else {
		$title = mysqli_real_escape_string( $dbc , trim($_POST['title']) );
		$title = strip_tags($title);
	}
	
	if( empty( $_POST['location'] ) ){
		if( empty( $db_address ) ){
			$errors[] = 'Your Address input box is empty';
		}else{
			$location = $db_address ;
		}
	} else {
		$location = mysqli_real_escape_string( $dbc , trim($_POST['location']) );
		$location = strip_tags($location);
	}
	
	if( empty( $_POST['short_desc'] ) ){
		if( empty( $db_desc ) ){
			$errors[] = 'Your Description input box is empty';
		}else{
			$short_desc = $db_desc ;
		}
	} else {
		$short_desc = mysqli_real_escape_string( $dbc , trim($_POST['short_desc']) );
		$short_desc = strip_tags($short_desc);
		if( strlen($short_desc) < 150 ){
			$errors[] = 'Your job description is too short. It must be at least 150 characters.';
		}
	}
	
	if( empty( $_POST['gen_loc'] ) ){
		if( empty( $db_location ) ){
			$errors[] = 'Your Job Location (state) selection box is not chosen';
		}else{
			$gen_loc = $db_location ;
		}
	} else {
		$gen_loc = mysqli_real_escape_string( $dbc , trim($_POST['gen_loc']) );
		$gen_loc = strip_tags($gen_loc);
	}
	
	if( empty( $_POST['gen_typ'] ) ){
		if( empty( $db_type ) ){
			$errors[] = 'Your Type of Work selection box is not chosen';
		}else{
			$gen_typ = $db_type ;
		}
	} else {
		$gen_typ = mysqli_real_escape_string( $dbc , trim($_POST['gen_typ']) );
		$gen_typ = strip_tags($gen_typ);
	}
	
	if( empty( $_POST['gen_sal'] ) ){
		if( empty( $db_income ) ){
			$errors[] = 'Your Salary selection box is not chosen';
		}else{
			$gen_sal = $db_income ;
		}
	} else {
		$gen_sal = mysqli_real_escape_string( $dbc , trim($_POST['gen_sal']) );
		$gen_sal = strip_tags($gen_sal);
	}
	
	if( empty( $_POST['gen_cat'] ) ){
		if( empty( $db_cat ) ){
			$errors[] = 'Your Category selection box is not chosen';
		}else{
			$gen_cat = $db_cat ;
		}
	} else {
		$gen_cat = mysqli_real_escape_string( $dbc , trim($_POST['gen_cat']) );
		$gen_cat = strip_tags($gen_cat);
	}

	if( empty( $_POST['noemail'] ) ){
		$noemail = 0;
	}else{
		$noemail = 1;
	}

	$the_job['JobName'] = $title;
	$the_job['Description'] = $short_desc;
	$the_job['JobAddress'] = $location;
	$the_job['Location'] = $gen_loc;
	$the_job['Income'] = $gen_sal;
	$the_job['Category'] = $gen_cat;
	$the_job['TypeTime'] = $gen_typ;
	$the_job['NoEmail'] = $noemail;
	
	if( empty($errors) && !$dont_submit_db && empty($query_code) ){
		list($message , $query_code) = submit_job_ini( $dbc , $title , $short_desc , $location , $gen_loc , $gen_sal , $gen_cat , $gen_typ , $user_array['Salt'] , $noemail );
		if(!empty($message)){
			$errors[] = $message;
		}else{
			$clear_repeat = true ;
			//direct to job_submit_req
			//needs to be done through javascript so that the form is submited via post not get
		}
	}elseif( empty($errors) && !$dont_submit_db && !empty($query_code) && $_POST['reload_it'] ){
		$message = update_job( $dbc , $the_job , $query_code );
		if(!empty($message)){
			$errors[] = $message;
		}else{
			$clear_repeat = true ;
			//direct to job_submit_req
			//needs to be done through javascript so that the form is submited via post not get
		}
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

		<?php if( (!empty($errors)) && ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') ) : ?>
	
			<div class="right_art errors">
				<div class="text">
	
				<?php
				echo "<p>The following things still need to be checked :</p>";
				foreach( $errors as $error ){ echo "<p>" . $error . "</p>" ; }
				?>
	
				</div><!-- text -->
			</div><!-- art_right -->
	
		<?php endif; ?>
	
		<?php if( $clear_repeat ) : ?>
		
			<div class="right_art">
				<div class="text">
					<p>Loading...</p>
				</div><!-- text -->
			</div><!-- right_art -->
			
			<form method="POST" id="frm1" action="job_submit_req.php">
				<input type="hidden" name="q" value="<?php echo $query_code ; ?>">
				<input type="hidden" name="dont_submit_db" value="true">
			</form>
			
			<script type="text/javascript">
				$(document).ready(function(){
					 $('#frm1').submit();
				});
			</script>
	
		<?php else : ?>
	
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
				<?php else : ?>
					<input type="submit" class="job_steps" value="Details" style="cursor:auto;">
					<input type="submit" class="job_steps" value="Requirements" style="cursor:auto;">
					<input type="submit" class="job_steps" value="Preview" style="cursor:auto;">
				<?php endif; ?>
				<div class="updating_data_info">
						<p>Jobsession is customer friendly, contact us anytime for support.</p>
				</div><!-- updating_data_info -->
				<form action="job_submit_ini.php" method="POST" id="job_sub_form">
					<div class="half_box_long">
						<p>Insert the Job Position Title : </p>
					</div>
					<input class="inf_long" placeholder="Title" type="text" name="title" maxlength="30" value="<?php if(!empty($_POST['title'])){ echo $_POST['title']; }elseif(!empty($db_title)){ echo $db_title ; } ?>">
					<div class="half_box_long">
						<p>Insert the Job Address : </p>
					</div>
					<input class="inf_long" placeholder="Location" type="text" name="location" value="<?php if(!empty($_POST['location'])){ echo $_POST['location']; }elseif(!empty($db_address)){ echo $db_address ; }elseif(!empty($user_array['Address1'])){ echo $user_array['Address1']; } ?>">
					<div class="half_box_long" style="height: 28px;width: 640px;">
						<p>Job Description ( minimum 150 characters, <span class="countdown"></span> ) : </p>
					</div>
					<script type="text/javascript">
						function updateCountdown() {
						    var remaining = jQuery('.message').val().length;
						    jQuery('.countdown').text('there are ' + remaining + ' so far');
						}
						jQuery(document).ready(function($) {
						    updateCountdown();
						    $('.message').change(updateCountdown);
						    $('.message').keyup(updateCountdown);
						});
					</script>
					<textarea class="inf_long_textarea message" placeholder="Description" maxlength="1000" name="short_desc"><?php if(!empty($_POST['short_desc'])){ echo $_POST['short_desc']; }elseif(!empty($db_desc)){ echo $db_desc ; } ?></textarea>
					<div class="half_box_long">
						<p>Insert the Job Location (state) : </p>
					</div>
					<div class="half_box_long">
						<select name="gen_loc" form="job_sub_form" class="select_tag">
							<?php if(!empty($_POST['gen_loc'])) : ?>
								<option value="<?php echo $_POST['gen_loc']; ?>"><?php echo $_POST['gen_loc']; ?></option>
							<?php elseif(!empty($db_location)) : ?>
								<option value="<?php echo $db_location; ?>"><?php echo $db_location; ?></option>
							<?php else : ?>
								<option value="">Choose Location</option>
							<?php endif ; ?>
							<option value='Australian Capital Territory'>Australian Capital Territory</option>
							<option value='Nothern Territory'>Nothern Territory</option>
							<option value='New South Wales'>New South Wales</option>
							<option value='Queensland'>Queensland</option>
							<option value='South Australia'>South Australia</option>
							<option value='Tasmania'>Tasmania</option>
							<option value='Victoria'>Victoria</option>
							<option value='Western Australia'>Western Australia</option>
						</select>
					</div>
					<div class="half_box_long">
						<p>Insert the Allocated Salary : </p>
					</div>
					<div class="half_box_long">
						<select name="gen_sal" form="job_sub_form" class="select_tag">
							<?php if(!empty($_POST['gen_sal'])) : ?>
								<option value="<?php echo $_POST['gen_sal']; ?>"><?php echo $_POST['gen_sal']; ?></option>
							<?php elseif(!empty($db_income)) : ?>
								<option value="<?php echo $db_income; ?>"><?php echo $db_income; ?></option>
							<?php else : ?>
								<option value="">Choose Salary</option>
							<?php endif ; ?>
							<option value="To Be Discussed">To Be Discussed</option>
							<option value="$0 - $25k">$0 - $25k</option>
							<option value="$25k - $50k">$25k - $50k</option>
							<option value="$50k - $75k">$50k - $75k</option>
							<option value="$75k - $100k">$75k - $100k</option>
							<option value="$100k - $150k">$100k - $150k</option>
							<option value="$150k+">$150k+</option>
						</select>
					</div>
					<div class="half_box_long">
						<p>Insert the Type of Work it is : </p>
					</div>
					<div class="half_box_long">
						<select name="gen_typ" form="job_sub_form" class="select_tag">
							<?php if(!empty($_POST['gen_typ'])) : ?>
								<option value="<?php echo $_POST['gen_typ']; ?>"><?php echo $_POST['gen_typ']; ?></option>
							<?php elseif(!empty($db_type)) : ?>
								<option value="<?php echo $db_type; ?>"><?php echo $db_type; ?></option>
							<?php else : ?>
								<option value="">Choose Type of Work</option>
							<?php endif ; ?>
							<option value="To Be Discussed">To Be Discussed</option>
							<option value="Full Time">Full Time</option>
							<option value="Part Time">Part Time</option>
							<option value="Casual">Casual</option>
							<option value="Contract">Contract</option>
							<option value="Temporary">Temporary</option>
						</select>
					</div>
					<div class="half_box_long">
						<p>Insert the Category of Employment : </p>
					</div>
					<div class="half_box_long">
						<select name="gen_cat" form="job_sub_form" class="select_tag">
							<?php if(!empty($_POST['gen_cat'])) : ?>
								<option value="<?php echo $_POST['gen_cat']; ?>"><?php echo $_POST['gen_cat']; ?></option>
							<?php elseif(!empty($db_cat)) : ?>
								<option value="<?php echo $db_cat; ?>"><?php echo $db_cat; ?></option>
							<?php else : ?>
								<option value="">Choose Category</option>
							<?php endif ; ?>
							<option value="Accounting">Accounting</option>
							<option value="Administration">Administration</option>
							<option value="Advertising, Arts and Media">Advertising, Arts and Media</option>
							<option value="Banking and Financial Services">Banking and Financial Services</option>
							<option value="Call Centre and Customer Service">Call Centre and Customer Service</option>
							<option value="CEO and General Management">CEO and General Management</option>
							<option value="Community Services and Development">Community Services and Development</option>
							<option value="Construction">Construction</option>
							<option value="Consulting and Strategy">Consulting and Strategy</option>
							<option value="Design and Architecture">Design and Architecture</option>
							<option value="Education and Training">Education and Training</option>
							<option value="Engineering">Engineering</option>
							<option value="Farming, Animals and Conservation">Farming, Animals and Conservation</option>
							<option value="Government and Defence">Government and Defence</option>
							<option value="Healthcare and Medical">Healthcare and Medical</option>
							<option value="Hospitality and Tourism">Hospitality and Tourism</option>
							<option value="Human Resources and Recruitment">Human Resources and Recruitment</option>
							<option value="Information and Communication Technology">Information and Communication Technology</option>
							<option value="Insurance and Superannuation">Insurance and Superannuation</option>
							<option value="Legal">Legal</option>
							<option value="Manufacturing, Transportation and Logistics">Manufacturing, Transportation and Logistics</option>
							<option value="Marketing and Communications">Marketing and Communications</option>
							<option value="Mining, Resources and Energy">Mining, Resources and Energy</option>
							<option value="Real Estate and Property">Real Estate and Property</option>
							<option value="Retail and Consumer Products">Retail and Consumer Products</option>
							<option value="Sales">Sales</option>
							<option value="Science and Technology">Science and Technology</option>
							<option value="Self Employment">Self Employment</option>
							<option value="Sport and Recreation">Sport and Recreation</option>
							<option value="Trades and Services">Trades and Services</option>
						</select>
					</div>
					<div class="half_box_long" style="width:500px;">
						<label>
							<p><input type="checkbox" name="noemail" style="display:inline;" value="1" <?php if($db_ne == 1){echo "checked";} ?>>
							Email me the QuickCV details of every applicant.</p>
						</label>
					</div><!-- some_space -->
					<?php if( !empty($query_code) ) : ?>
						<input type="hidden" name="q" value="<?php echo $query_code ; ?>">
						<input type="hidden" name="reload_it" value="true">
					<?php endif; ?>
					<input type="submit" class="submit_inf , submit_button" value="Save">
				</form>
			</div><!-- upd_inf -->	
		
		<?php endif; ?>
		
		</div><!-- main_right -->
	</div><!-- main_content -->
</div><!-- center_it -->

<?php
include("includes/footer.php");
?>