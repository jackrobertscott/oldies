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

if( !$_SESSION['log'] ){
	header('Location: no_access.php');
	exit();
}

if( $user_array['QuickCV'] ){
	$qaf_query = "SELECT * FROM QuickCV WHERE UserId = '$id'";
	$qaf_action = mysqli_query( $dbc , $qaf_query );
	if( $qaf_action ){
		$quick_data = mysqli_fetch_assoc( $qaf_action );
		$af_desc = $quick_data['About'];
		$af_edu = $quick_data['Education'];
		$af_wexp = $quick_data['Experience'];
		$af_fname = $quick_data['FirstName'];
		$af_lname = $quick_data['LastName'];
		$af_res = $quick_data['Resume'];
	} else {
		$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc );
	}
}

if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST'){

	if( $_FILES['resume']['size'] != 0 || !empty($_FILES['resume']['name']) ){
				
		$submit_ext = array("doc", "docx", "gif", "pdf", "rtf", "tiff", "txt", "xls", "xlsx");
		$res_ext = strtolower(end(explode('.' , $_FILES['resume']['name'])));
		
		if(!in_array($res_ext, $submit_ext)){
			$errors[] = 'Your resume is of an incorrect file type. Try .pdf .doc .docx or .txt as the file type.';
		}

		$allowedMimeTypes = array('application/msword','text/pdf','image/gif','image/jpeg','image/png');

		if(in_array( $_FILES["file"]["type"], $allowedMimeTypes)){
			$errors[] = 'Your resume is of an incorrect file type. Try .pdf .doc .docx or .txt as the file type.';
		}

		if($image_size > 200000){
			$errors[] = 'Your image is beyond the maximum file size (200KB)';
		}

	}

	if( empty( $_POST['FirstName'] ) ){
		if( empty( $af_fname ) ){
			$errors[] = 'You must enter your first name';
		}else{
			$firstname_me = $af_fname ;
		}
	} else {
		$firstname_me = mysqli_real_escape_string( $dbc , trim($_POST['FirstName']) );
		$firstname_me = strip_tags($firstname_me);
	}

	if( empty( $_POST['LastName'] ) ){
		if( empty( $af_lname ) ){
			$errors[] = 'You must enter your last name';
		}else{
			$lastname_me = $af_lname ;
		}
	} else {
		$lastname_me = mysqli_real_escape_string( $dbc , trim($_POST['LastName']) );
		$lastname_me = strip_tags($lastname_me);
	}

	if( empty( $_POST['desc_me'] ) ){
		if( empty( $af_desc ) ){
			$errors[] = 'Please fill in your Description input box.';
		}else{
			$desc_me = $af_desc ;
		}
	} else {
		$desc_me = mysqli_real_escape_string( $dbc , trim($_POST['desc_me']) );
		$desc_me = strip_tags($desc_me);
	}
	
	if( empty( $_POST['edu_me'] ) ){
		if( empty( $af_edu ) ){
			$errors[] = 'Please fill in your Education input box.';
		}else{
			$edu_me = $af_edu ;
		}
	} else {
		$edu_me = mysqli_real_escape_string( $dbc , trim($_POST['edu_me']) );
		$edu_me = strip_tags($edu_me);
	}
	
	if( empty( $_POST['wexp_me'] ) ){
		if( empty( $af_wexp ) ){
			$errors[] = 'Please fill in your Work Experience input box.';
		}else{
			$wexp_me = $af_wexp ;
		}
	} else {
		$wexp_me = mysqli_real_escape_string( $dbc , trim($_POST['wexp_me']) );
		$wexp_me = strip_tags($wexp_me);
	}

	if( empty($errors) ){
		$message = build_quick_cv( $dbc , $desc_me , $edu_me , $wexp_me , $user_array['QuickCV'] , $firstname_me , $lastname_me, $user_array['Email'] );
		if(!empty($message)){
			$errors[] = $message;
		}else{
			if( $_FILES['resume']['size'] != 0 || !empty($_FILES['resume']['name']) ){
				$uploadfile = 'uploads/resume_files/' . $firstname_me . '_' . $lastname_me . '_' . $id . '_'. $_FILES['resume']['name'];
				$fileContent = file_get_contents($_FILES['resume']['tmp_name']);
				if (move_uploaded_file($_FILES['resume']['tmp_name'], $uploadfile)) {
					$input_resume = "UPDATE QuickCV SET Resume = '$uploadfile' WHERE UserId = '$id'";
					$resume_query = mysqli_query( $dbc , $input_resume ) ;
					if( !$resume_query ){
						$error[] = 'Error! : ' . mysqli_error( $dbc ) ;
					}
				} else {
				    $errors[] = "File Uploade Error " . $_FILES['resume']['error'] ;
				}
			}
			if(empty($errors)){
				$all_good_cv = true ;
			}
		}
	}
	
}

$page_title = "Quick CV";
include('includes/header.php');
?>

<?php include('includes/log_in_bar.php'); ?>

<div class="center_it">
	<div class="main_content" >
	
	<?php include ('includes/sidebar.php'); ?>
	
		<div class="main_right">

			<?php if( $_SESSION['ver'] != 2 ) : ?>

				<div class="right_art">
					<div class="text">
						<p>You need to <a href="verify_account.php">VERIFY</a> your account before you can make a Quick CV</p>
					</div><!-- text -->
				</div><!-- art_right -->

			<?php else : ?>

				<?php if( (!empty($errors)) && ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') ) : ?>
			
					<div class="right_art errors">
						<div class="text">
			
						<?php
						echo "<p>The following things still need to be checked :</p>";
						foreach( $errors as $error ){ echo "<p>" . $error . "</p>" ; }
						?>
			
						</div><!-- text -->
					</div><!-- art_right -->
			
				<?php elseif( $all_good_cv ) : ?>

					<div class="right_art">
						<div class="text">
							<p>Your Quick CV was successfully updated.</p>
						</div><!-- text -->
					</div><!-- art_right -->

				<?php endif ; ?>
					
				<div class="upd_inf">
					<div class="updating_data_info">
						<?php if(!$user_array['QuickCV']) : ?>
							<p>To Apply for Jobs you need to fill out your Quick CV</p>
						<?php else : ?>
							<p>Update your Quick CV details</p>
						<?php endif ; ?>
					</div><!-- updating_data_info -->
					<form action="quick_cv.php" enctype="multipart/form-data" method="POST">
						<div class="half_box_long">
							<p>*First Name : </p>
						</div>
						<input class="inf_long" placeholder="First Name" type="text" name="FirstName" maxlength="32" value="<?php if(!empty($_POST['FirstName'])){ echo $_POST['FirstName']; }elseif(!empty($af_fname)){ echo $af_fname ; } ?>">
						<div class="half_box_long">
							<p>*Last Name : </p>
						</div>
						<input class="inf_long" placeholder="Last Name" type="text" name="LastName" maxlength="32" value="<?php if(!empty($_POST['LastName'])){ echo $_POST['LastName']; }elseif(!empty($af_lname)){ echo $af_lname ; } ?>">
						<div class="half_box_long" style="height: 28px;width: 640px;">
							<p>*Describe yourself (include all things from hobbies to sports to passions and aspirations) : </p>
						</div>
						<textarea class="inf_long_textarea" placeholder="Description" maxlength="1000" name="desc_me"><?php if(!empty($_POST['desc_me'])){ echo $_POST['desc_me']; }elseif(!empty($af_desc)){ echo $af_desc ; } ?></textarea>
						<div class="half_box_long" style="height: 28px;width: 640px;">
							<p>*Describe your education (include times and places eg. A University, Perth 2003 - 2008) : </p>
						</div>
						<textarea class="inf_long_textarea" placeholder="Education" maxlength="1000" name="edu_me"><?php if(!empty($_POST['edu_me'])){ echo $_POST['edu_me']; }elseif(!empty($af_edu)){ echo $af_edu ; } ?></textarea>
						<div class="half_box_long" style="height: 28px;width: 640px;">
							<p>*Your work experience (include times and places eg. A Work Place, Perth 2009 - 2011) : </p>
						</div>
						<textarea class="inf_long_textarea" placeholder="Work Experience" maxlength="1000" name="wexp_me"><?php if(!empty($_POST['wexp_me'])){ echo $_POST['wexp_me']; }elseif(!empty($af_wexp)){ echo $af_wexp ; } ?></textarea>
						<div class="half_box_long">
							<?php if(empty($af_res)){ echo '<p>[optional] Upload a Resume : </p>'; }else{ echo '<p>Upload a New Resume</p>'; } ?>
						</div>
						<input class="inf_long" type="file" name="resume" value="Resume">
						<input type="submit" class="submit_inf , submit_button" value="Save Quick CV">
					</form>
				</div><!-- upd_inf -->

			<?php endif ; ?>
				
		</div><!-- main_right -->
	</div><!-- main_content -->
</div><!-- center_it -->

<?php
include('includes/footer.php');
?>