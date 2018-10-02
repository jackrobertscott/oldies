<?php
/**
*
* Create a new Job
*
* Jack Scott
*
*/
function submit_job_ini( $dbc , $title = '' , $short_desc = '' , $location = '' , $gen_loc = '' , $gen_sal = '' , $gen_cat = '' , $gen_typ = '' , $salt = '' , $noemail = '' ){

	$current_id = $_SESSION['id'];
	
	$emp_query_kqs = "SELECT JobLimit FROM Employers WHERE UserId = '$current_id'";
	$emp_data_kqs = mysqli_query( $dbc , $emp_query_kqs );
	
	if( $emp_data_kqs ){
		$emp_var_kqs = mysqli_fetch_assoc( $emp_data_kqs );
		$tick_bef = $emp_var_kqs['JobLimit'];
		if( $tick_bef < 20 ){ //This number represents the max number of jobs an employer can submit
			$tick_bef ++ ; 
			$job_ticker = "UPDATE Employers SET JobLimit = '$tick_bef' WHERE UserId = '$current_id'";
			
			if(!mysqli_query($dbc , $job_ticker)){
				$message = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
				return $message;
			}else{
			
				//The initial Query
				$job_submit_query = "INSERT INTO JobSubmit ( UserId )
								VALUES ( '$current_id' )" ;
				$insert = mysqli_query( $dbc , $job_submit_query ) ;
				$query_id = mysqli_insert_id( $dbc );
				
				//Check if Query succeeds 
				if( !$insert ){
					$message = 'Error! : ' . mysqli_error( $dbc ) ;
				}else{
					$time_now = date("F j, Y");
					$query_code = sha1($query_id . $salt);
					$job_submit_query_other = "UPDATE JobSubmit SET JobName = '$title' ,
																	TimeOfSubmit = '$time_now' ,
																	Description = '$short_desc' ,
																	JobAddress = '$location' ,
																	Location = '$gen_loc' ,
																	Income = '$gen_sal' ,
																	Category = '$gen_cat' ,
																	TypeTime = '$gen_typ' ,
																	NoEmail = '$noemail' ,
																	QueryCode = '$query_code'
																	WHERE Id = '$query_id'" ;
					$insert_other = mysqli_query( $dbc , $job_submit_query_other ) ;
					if( !$insert_other ){
						$message = 'Error! : ' . mysqli_error( $dbc ) ;
					}
				}
				
			}
		}else{
			$message = "You have allready reached the maximum amount of Job Applications" ;
			return $message;
		}
	} else {
		$message = 'Error! : ' . mysqli_error( $dbc ) ;
		return $message;
	}
	
	$data_return = array("$message" , "$query_code"); 
	
return $data_return;

}

/**
*
* Add the requirement array to the Job specified
*
* Jack Scott
*
*/
function submit_job_req( $dbc , $insert_reqs = '' , $query_code = '' ){

	$current_id = $_SESSION['id'];
			
	//The initial Query
	$update_reqs = "UPDATE JobSubmit SET RequirementArray = '$insert_reqs' WHERE QueryCode = '$query_code'";

	if(!mysqli_query($dbc , $update_reqs)){
		$message = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
		return $message;
	}

}

/**
*
* Add a banner image to the Job specified
*
* Jack Scott
*
*/
function submit_job_dec( $dbc , $image_tmp = '' , $image_ext = '' , $query_code = '' ){

	$current_id = $_SESSION['id'];
	
	if( $image_tmp ){
		$tmp_image_dir_name = upload_tmp_img( $image_tmp , $image_ext , $query_code );
		$display_query = "UPDATE JobSubmit SET DisplayImage = '$tmp_image_dir_name' WHERE QueryCode = '$query_code'";
		$insert_display = mysqli_query ( $dbc , $display_query ) ;
		if( !$insert_display ){
			$message = 'Error! : ' . mysqli_error( $dbc ) ;
			return $message;
		}
	}
	
	$data_return = array("$message" , "$tmp_image_dir_name"); 
	
return $data_return;

}

/**
*
* Return the dimensions of the banner image
*
* Jack Scott
*
*/
function return_new_dimensions( $width = '' , $height = '' , $box_width = '' , $box_height = '' ){
	$box_ratio = ($box_width / $box_height) ;
	$image_ratio = ($width / $height) ;
	if( $box_ratio > $image_ratio ){
		$new_width = $box_width ;
		$new_height = ($box_width / $image_ratio) ;
	}else{
		$new_height = $box_height ;
		$new_width = ($image_ratio * $box_height) ;
	}
	$dimensions = array("$new_width" , "$new_height");
	return $dimensions ;
}

/**
*
* Copy the submited banner image and add that copy to a specific folder to be accessed later
*
* Jack Scott
*
*/
function upload_tmp_img( $image_tmp , $image_ext , $query_code ){

	$tmp_directory = 'uploads/tmp_images' ;
	$image_code = sha1($query_code); //creates a random name for the image
	$tmp_image_dir_name = "$tmp_directory/$image_code" . "_" . time() . ".jpeg" ;

	//should eventualy put the tmp_image folder into the uploads folder then change the path to upload to that

	list($width , $height) = getimagesize($image_tmp);
	$box_height = 160;
	$box_width = 672;
	list($new_width , $new_height) = return_new_dimensions($width , $height , $box_width , $box_height );
	$image_dup_ready = imagecreatetruecolor($new_width, $new_height);
	
	if( ($image_ext == 'jpg') || ($image_ext == 'jpeg') ){
		$image_copy = imagecreatefromjpeg($image_tmp);
	}elseif( $image_ext == 'png' ){
		$image_copy = imagecreatefrompng($image_tmp);
	}else{
		$message = "[error with upload_tmp_img. image ext = $image_ext ]";
		return $message ;
	}
	
	imagecopyresampled($image_dup_ready, $image_copy, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	
	imagejpeg( $image_dup_ready , $tmp_image_dir_name , 100);
	
	imagedestroy($image_dup_ready);
	imagedestroy($image_copy);
	
	return $tmp_image_dir_name ;
}

/**
*
* Update the values of the Job data table 
*
* Jack Scott
*
*/
function update_job( $dbc , $the_job = '' , $query_code = '' ){

	foreach( $the_job as $key => $value ){

		$query = "UPDATE JobSubmit SET " . $key . " = '" . $value . "' WHERE QueryCode = '$query_code'";
		if(!mysqli_query($dbc , $query)){
			$message = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
			return $message;
		}

	}

}

/**
*
* This function creates a new row insert to the AppliedJobs data table 
* The row includes the job and the user id's which can be searched for when finding the jobs each user has applied for
*
* Jack Scott
*
*/
function apply_for_job( $dbc , $job_id_here = '' , $direction = '' , $emp_id = '' , $email_applicant = ''){
	//get the current users id
	$id = $_SESSION['id'];
	//email the appliaction to the employer
	$universal_query = "SELECT * FROM JobSubmit WHERE Active = '1' AND Id = '$job_id_here'";
	$universal_action = mysqli_query( $dbc , $universal_query );
	$qaf_query = "SELECT * FROM QuickCV WHERE UserId = '$id'";
	$qaf_action = mysqli_query( $dbc , $qaf_query );
	if( !$universal_action || !$qaf_action ){
		$message = 'There is a promblem with our server : ' . mysqli_error( $dbc );
		return $message;
	}else{
		//get the information needed for email application
		$uni_data = mysqli_fetch_assoc( $universal_action );
		$quick_data = mysqli_fetch_assoc( $qaf_action );
		//if the email was successfully sent then add the applied job with the user to the AppliedJobs data table
		$time_now = date("F j, Y");
		$add_the_job_query = "INSERT INTO AppliedJobs ( JobId , UserId , TimeOfApp , EmpId )
							VALUES ( '$job_id_here' , '$id' , '$time_now' , '$emp_id' )" ;
		$insert_app = mysqli_query( $dbc , $add_the_job_query );
		//check if the appliaction to AppliedJobs data table was successful
		if(!$insert_app){
			$message = 'There is a promblem with our server : ' . mysqli_error( $dbc );
			return $message;
		}
		if($uni_data['NoEmail'] == 1){
			global $email_des;
			$email = new PHPMailer();
			$email->isHTML(true);
			$email->Subject   = 'Jobsession - Application for Listed Job : ' . $uni_data['JobName'];
			$email->FromName   = 'Jobsession';
			$email->Body      = $email_des['header'] .
								'<p><u>Job Applied for on</u> : ' . date('l jS \of F Y h:i:s A') . '</p>' .
								'<p><u>Applicant Name</u> : ' . $quick_data['FirstName'] . ' ' . $quick_data['LastName'] . '</p>' .
								'<p><u>Applicant Email</u> : ' . $email_applicant . '</p>' .
								'<p><u>About the Applicant</u> :<br><br>' . $quick_data['About'] . '</p>' .
								'<p><u>Education</u> :<br><br>' . $quick_data['Education'] . '</p>' .
								'<p><u>Work Experience</u> :<br><br>' . $quick_data['Experience'] . '</p>' .
								$email_des['footer'] ;
			$email->AddAddress( $direction );
			$file_to_attach = 'resume_files/resume_' . $id ;
			$email->AddAttachment( $file_to_attach , 'Resume' );
			//check if the email was successfully sent
			if(!$email->send()) {
				$message = "There is something wrong with our servers, your application was unable to be sent. direction " . $direction;
				return $message;
			}
		}
	}
}
?>