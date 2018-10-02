<?php
/**
*
* submitData function takes the username password and email input that the user submits from user_reg.php
* It then adds a salt to the password and encrypts it
* the data values are then submitted into the Users database as a new row (new user)
*
* Jack Scott
*
*/
function submitData( $username = '' , $password = '' , $useremail = '' , $dbc ){

	//randomly generate a salt
	$salt = SHA1( rand() . time() );
	//add the salt to the password
	$encodedPass = SHA1($password . $salt);
	//randomly generate a verification code
	$verificationCode = SHA1( rand() . time() );
	//get the time in a user friendly format
	$time_now = date('H:i:s d-m-y');
	//create a new row for the user in the database
	$input_query = "INSERT INTO Users ( Username , Password , Email , TimeOfRegistration , VerCode , Salt )
					VALUES ( '$username' , '$encodedPass' , '$useremail' , '$time_now' , '$verificationCode' , '$salt' )" ;
	$insert = mysqli_query( $dbc , $input_query ) ;
	//check if the query works
	if( !$insert ){
		$message = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
		return $message;
	} else {
		//if the user row creation is successful, send a varification email to the users email
		global $email_des;
		$email = new PHPMailer();
		$email->isHTML(true);
		$email->Subject   = 'Jobsession Account Verification';
		$email->FromName   = 'Jobsession';
		$email->Body      = $email_des['header'] .
			'<h3>Welcome to Jobsession!</h3>' .
			'<p>To verify your account, click this link: <a href="http://jobsession.com.au/verify_account.php?ver_link_code=' . $verificationCode . '">Verify Account</a>' .
			'<p>Or enter your verification code in manualy: ' . $verificationCode . '</p>' .
			'<p>To verify your account, enter the above code into the verification code input box and press submit! Easy!</p>' .
			$email_des['footer'] ;
		$email->AddAddress( $useremail );
		//check if the email was successfully sent
		if(!$email->send()) {
			$message = "There is something wrong with our servers, your verification email was unable to be sent to " . $useremail;
			return $message;
		}
		//the users account has been created but they are not yet logged in 
		//the checkUser function is called to log the user in and set the $_SESSION array
		$message = checkUser( false , $username , $password , $dbc );
	}
	return $message;

}

/**
*
* This function check if the username the user enters is already in use by another account of not
*
* Jack Scott
*
*/
function checkIfGoneUser( $username , $dbc ){
	
	//create a query to check if there are any rows matching the entered username
	$search_query = "SELECT Id FROM Users WHERE Username = '$username'" ;
	$auth = mysqli_query ( $dbc , $search_query ) ;
	//check if the query entry gets to the database successfully
	if( $auth ){
		//get the number of rows matching the query (users with that username)
		$num = mysqli_num_rows( $auth );
		//if there are more then 0 rows with that username then it is in use and should return true (the username is in use)
		if( $num > 0 ){ return true ; }else{ return false ; }
	//if the query doesn't successfully reach the database then create an error
	} else {
		$message = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
		return $message; 
	}
	
}

/**
*
* An email is taken as a parameter, that email is then checked against the data base to see if its already in use
* If the email is in use already, then it will return true
*
* Jack Scott
*
*/
function checkIfGoneEmail( $email , $dbc ){
	
	//create query to find the rows matching the email parameter
	$search_query1 = "SELECT Id FROM Users WHERE Email = '$email'" ;
	$search_query2 = "SELECT Id FROM Employers WHERE EmailForApplications = '$email'" ;
	$auth1 = mysqli_query ( $dbc , $search_query1 ) ;
	$auth2 = mysqli_query ( $dbc , $search_query2 ) ;
	//check if the querys reach the database successfully
	if( ($auth1) && ($auth2) ){
		//count the number of matching rows for each query
		$num1 = mysqli_num_rows( $auth1 );
		$num2 = mysqli_num_rows( $auth2 );
		//if the number of rows is greater the 0 then the email is taken and the function return true
		if( $num1 > 0 || $num2 > 0 ){ return true ; }else{ return false ; }
	//if the query doesn't reach the database, and error message is created
	} else {
		$message = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
		return $message; 
	}
	
}

/**
*
* This function takes the username (username or email) and password submited by the user as parameters
* If the values match a row in the database, the user will be logged in and the $_SESSION array will be created
*
* Jack Scott
*
*/
function checkUser( $keepin = '' , $username = '' , $password = '' , $dbc ){
	//create the queries to get the user data
	$username_query = "SELECT Id, Username, EmployerStatus, Verified, Salt, Password FROM Users WHERE Username = '$username'" ;
	$email_query = "SELECT Id, Username, EmployerStatus, Verified, Salt, Password FROM Users WHERE Email = '$username'" ;
	$sender_username = mysqli_query ( $dbc , $username_query ) ;
	$sender_email = mysqli_query ( $dbc , $email_query ) ;
	//check that the queries successfully reach the database
	if( $sender_username && $sender_email ){
		//get the number of rows that match the queries
		$num_username = mysqli_num_rows( $sender_username );
		$num_email = mysqli_num_rows( $sender_email );
		//if the username query matches a row then evaluate the password and log in if correct
		if( $num_username == 1 ){
			//get the values that match the query request
			$quick_array = mysqli_fetch_assoc( $sender_username );
			//encode the password input by the user with the Salt
			$enc_pass = SHA1( $password  . $quick_array['Salt'] );
			//check if the encoded password input by user and one in database match
			//log the user in if they match and return and error if they do not
			if($quick_array['Password'] == $enc_pass){
				if($keepin){
					setcookie("id", $quick_array['Id']);
				}
				$_SESSION['id'] = $quick_array['Id'];
				$_SESSION['user'] = $quick_array['Username'] ;
				$_SESSION['log'] = TRUE ;
				$_SESSION['es'] = $quick_array['EmployerStatus'];
				$_SESSION['ver'] = $quick_array['Verified'];
			}else{
				$message = 'You have inserted incorrect values, try again. <br>Be careful of uppercase and lowercase characters in your password';
				return $message;
			}
		//if the email query matches a row then evaluate the password and log in if correct
		} elseif( $num_email == 1 ){
			//get the values that match the query request
			$quick_array = mysqli_fetch_assoc( $sender_email );
			//encode the password input by the user with the Salt
			$enc_pass = SHA1( $password . $quick_array['Salt'] );
			//check if the encoded password input by user and one in database match
			//log the user in if they match and return and error if they do not
			if($quick_array['Password'] == $enc_pass){
				if($keepin){
					setcookie("id", $quick_array['Id']);
				}
				$_SESSION['id'] = $quick_array['Id'];
				$_SESSION['user'] = $quick_array['Username'] ;
				$_SESSION['log'] = TRUE ;
				$_SESSION['es'] = $quick_array['EmployerStatus'];
				$_SESSION['ver'] = $quick_array['Verified'];
			}else{
				$message = 'You have inserted incorrect values, try again. <br>Be careful of uppercase and lowercase characters in your password';
				return $message;
			}
		//check if either query returns duplicate users in the database
		//this is a fault in the database and needs to be addressed by Jobsession staff
		//should send an email or message to Jobsession informing of database fault **to be added in future**
		} elseif( $num_username > 1 || $num_email > 1 ){
			$message = 'There is an error with our servers - duplicate users.' ;
			return $message;
		//no rows match the username or password that was submitted by the user
		} else {
			$message = 'You have inserted incorrect values, try again. <br>Be careful of uppercase and lowercase characters in your password';
			return $message;
		}
	//if the queries do not successfully reach the database, return an error
	} else {
		$message = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
		return $message;
	}
	
	return $message;
}

/**
*
* If $_COOKIE['id'] is set then log in that user
*
* Jack Scott
*
*/
function log_in_with_cookie( $cookie_id = '' , $dbc ){
	//create the queries to get the user data
	$user_query = "SELECT Id, Username, EmployerStatus, Verified, Salt, Password FROM Users WHERE Id = '$cookie_id'" ;
	$sender_user = mysqli_query ( $dbc , $user_query ) ;
	//check that the queries successfully reach the database
	if( $sender_user ){
		//get the values that match the query request
		$quick_array = mysqli_fetch_assoc( $sender_user );
		$_SESSION['id'] = $quick_array['Id'];
		$_SESSION['user'] = $quick_array['Username'] ;
		$_SESSION['log'] = TRUE ;
		$_SESSION['es'] = $quick_array['EmployerStatus'];
		$_SESSION['ver'] = $quick_array['Verified'];
	}else{
		$message = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
		return $message;
	}
}

/**
*
* Check the old password with the users password
* If the passwords match, then change the password to the new password
*
* Jack Scott
*
*/
function change_password( $new_password = '' , $old_password = '' , $dbc ){

	//get the users id to add to query
	$current_user_id = $_SESSION['id'];
	//create a query to retrieve the users salt to encrypt password 
	$salt_query = "SELECT Salt FROM Users WHERE Id = '$current_user_id'";
	$salt_action = mysqli_query ( $dbc , $salt_query );
	//check if the query reaches the database successfully
	if(!$salt_action){
		$message = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
		return $message;
	}
	//retrieve the salt value
	$salt_fetch = mysqli_fetch_assoc( $salt_action );
	//encode the entered password with the salt to check if it matches the database password
	$encodedPass = SHA1($old_password . $salt_fetch['Salt']);
	//check if there are any matches in the database for the user and the entered password (authenticity)
	$search_query = "SELECT Id FROM Users WHERE Id = '$current_user_id' AND Password = '$encodedPass'" ;
	$sender = mysqli_query ( $dbc , $search_query ) ;
	//check that the query successfully reaches the database
	if( $sender ){
		//get the number of matches related to the user
		$num = mysqli_num_rows( $sender );
		//if the user does exist change the password
		if( $num == 1 ){ 
			//create the new encrypted password with the salt
			$new_encodedPass = SHA1($new_password . $salt_fetch['Salt']);
			//create the query to update the password
			$update_password = "UPDATE Users SET Password = '$new_encodedPass' WHERE Id = '$current_user_id'";
			//check if the query is successful else create an error
			if(!mysqli_query($dbc , $update_password)){
				$message = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
				return $message;
			}
		//check if there are duplicate users
		} elseif($num > 1) {
			//if duplicate users; create an error **should email to Jobsession in future**
			$message = 'Error! duplicate users found. Please contact Jobsession for support.';
			return $message;
		//if the users password does not equal the password entered
		} else {
			//create an error message
			$message = 'You have inserted incorrect values, try again. Be careful of uppercase and lowercase characters in your old password';
			return $message;
		}
	} else {
		//if the query does not complete, then create an error message
		$message = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
		return $message;
	}
	return $message;
	
}

/**
*
* Submit the changed account info into the Users and Employers databases
*
* Jack Scott
*
*/
function update_account_info( $user_details = '' , $emp_details = '' , $image_tmp = '' , $image_ext = '' , $dbc ){
	//get the users id for the queries to db
	$current_user_id = $_SESSION['id'];
	//use a foreach loop to iterate through each $user_detail value
	//each value in the array is submited to the database under their key value
	foreach( $user_details as $key => $value ){
		$query = "UPDATE Users SET " . $key . " = '" . $value . "' WHERE Id = '$current_user_id'";
		if(!mysqli_query($dbc , $query)){
			$message = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
			return $message;
		}

	}
	//use a foreach loop to iterate through each $emp_detail value
	//each value in the array is submited to the database under their key value
	foreach( $emp_details as $key => $value ){
		$query = "UPDATE Employers SET " . $key . " = '" . $value . "' WHERE UserId = '$current_user_id'";
		if(!mysqli_query($dbc , $query)){
			$message = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
			return $message;
		}

	}
	//update the users employer profile image if it exists
	//this creates two different size images with the picture (one for the employer profile the other for the job posts)
	if( $image_tmp ){
		list( $tmp_image_dir_name , $tmp_image_dir_name_thumb ) = upload_logo_img( $image_tmp , $image_ext );
		$display_query = "UPDATE Employers SET CompanyLogo = '$tmp_image_dir_name' WHERE UserId = '$current_user_id'";
		$insert_display = mysqli_query ( $dbc , $display_query ) ;
		if( !$insert_display ){
			$message = 'Error! : ' . mysqli_error( $dbc ) ;
		}
		$display_query_thumb = "UPDATE Employers SET CompanyThumb = '$tmp_image_dir_name_thumb' WHERE UserId = '$current_user_id'";
		$insert_display_thumb = mysqli_query ( $dbc , $display_query_thumb ) ;
		if( !$insert_display_thumb ){
			$message = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
			return $message;
		}
	}
	
	return $message;

}

/**
*
* This function takes a temporarily stored image and makes 2 copies
* The photo copies are then saved to their allocated folders to be accessed later on
*
* Jack Scott
*
*/
function upload_logo_img( $image_tmp , $image_ext ){

	$current_user_id = $_SESSION['id'];

	$tmp_directory = 'uploads/logo_images' ;
	$tmp_image_dir_name = "$tmp_directory/logo_" . $current_user_id . "_" . time() . ".jpeg" ;
	$tmp_directory_thumb = 'uploads/thumb_images' ;
	$tmp_image_dir_name_thumb = "$tmp_directory_thumb/logo_" . $current_user_id . "_" . time() . ".jpeg" ;

	list($width , $height) = getimagesize($image_tmp);
	$box_height = 200;
	$box_width = 200;
	list($new_width , $new_height) = return_new_dimensions($width , $height , $box_width , $box_height );
	$box_height = 92;
	$box_width = 92;
	list($new_width_thumb , $new_height_thumb) = return_new_dimensions($width , $height , $box_width , $box_height );
	$image_dup_ready = imagecreatetruecolor($new_width, $new_height);
	$image_dup_ready_thumb = imagecreatetruecolor($new_width_thumb, $new_height_thumb);
	
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
	imagecopyresampled($image_dup_ready_thumb, $image_copy, 0, 0, 0, 0, $new_width_thumb, $new_height_thumb, $width, $height);
	imagejpeg( $image_dup_ready_thumb , $tmp_image_dir_name_thumb , 100);
	
	imagedestroy($image_dup_ready);
	imagedestroy($image_dup_ready_thumb);
	imagedestroy($image_copy);
	
	$array_quick = array( "$tmp_image_dir_name" , "$tmp_image_dir_name_thumb" );
	
	return $array_quick ;
}

/**
*
* This function checks if the user is not already and employer
*
* Jack Scott
*
*/
function check_if_employer( $dbc ){
	//get the user id for use in query
	$user_id = $_SESSION['id'];
	//create query to check if matches for the user being employer
	$search_query = "SELECT Id FROM Employers WHERE UserId = '$user_id'" ;
	$auth = mysqli_query ( $dbc , $search_query ) ;
	//check if the query is successful
	if( $auth ){
		//get the number of matches for the query (if the user is employer)
		$num = mysqli_num_rows( $auth );
		//if the user is an employer ($num > 0) it returns true else false
		if( $num > 0 ){ return true ; }else{ return false ; }
	//if the query is not successful
	} else {
		//create an error message and return it
		$message = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
		return $message; 
	}

}

/**
*
* creates the employer account for the user
* adds the data submited by the user to the employer account
*
* Jack Scott
*
*/
function build_employer( $dbc ){

	//get the id of the user for the query
	$userid = $_SESSION['id'];
	//check that the user is not already and employer
	if( $_SESSION['ver'] != 2 ){
		$message = "You must verify your account before you can register as an employer" ;
		return $message;
	}
	//get the time to be submited to the database (human readable)
	$time_now = date('H:i:s d-m-y');
	//create the querys to insert into the new employer row
	$employ_query = "INSERT INTO Employers ( UserId , TimeRegister )
					VALUES ( '$userid' , '$time_now' )" ;
	$user_reg_employ = "UPDATE Users SET EmployerStatus = '2' WHERE Id = '$userid'";
	$activate_cv = "UPDATE Users SET QuickCV = '0' WHERE Id = '$user_id'";
	$activate_cv_query = mysqli_query ( $dbc , $activate_cv ) ;		
	$upd_emp_stat = mysqli_query ( $dbc , $user_reg_employ ) ;
	$insert_emp = mysqli_query ( $dbc , $employ_query ) ;
	//check that the querys reach database
	if( (!$insert_emp) || (!$upd_emp_stat) || (!$activate_cv_query) ){
		//if queries fail then create an error message
		$message = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
		return $message;
	} else {
		//create query to check if the employer row has been made
		$emp_query = "SELECT EmployerStatus FROM Users WHERE Id = '$userid'";
		$emp_data = mysqli_query( $dbc , $emp_query );
		//check if query works
		if( !$emp_data ){
			//if query doesnt work create an error message
			$message = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
			return $message;
		}else{
			//if query works and the employer is an employer, set the session variable to equal the new employer status
			$emp_var = mysqli_fetch_assoc( $emp_data );
			$_SESSION['es'] = $emp_var['EmployerStatus'];
		}
	}
	
	return $message;

}

/**
*
* create the quick_cv for the user with the data input by user
*
* Jack Scott
*
*/
function build_quick_cv( $dbc , $desc_me = '' , $edu_me = '' , $wexp_me = '' , $quick_cv = '' , $firstname_me = '' , $lastname_me = '' , $email = '' ){

	//get the id of the user to use in the query
	$user_id = $_SESSION['id'];
	//check if the user already has a quick_cv
	//if the user already has a quick_cv then update the quick_cv rather then submiting a new row
	if( $quick_cv ){ 
		//create the update query
		$job_submit_query_other = "UPDATE QuickCV SET About = '$desc_me' ,
														Education = '$edu_me' ,
														Experience = '$wexp_me' ,
														FirstName = '$firstname_me' ,
														LastName = '$lastname_me' ,
														Email = '$email'
														WHERE UserId = '$user_id'" ;
		$insert_other = mysqli_query( $dbc , $job_submit_query_other ) ;
		//check if the query reaches database else create error
		if( !$insert_other ){
			$message = 'Error! : ' . mysqli_error( $dbc ) ;
			return $message;
		}
	//if the quick_cv does not exist then create a new one with the input provided in the parameters
	} else {
		//create the quick_cv query
		$job_submit_query = "INSERT INTO QuickCV ( UserId , About , Education , Experience , FirstName , LastName )
						VALUES ( '$user_id' , '$desc_me' , '$edu_me' , '$wexp_me' , '$firstname_me' , '$lastname_me' )" ;
		$insert = mysqli_query( $dbc , $job_submit_query ) ;
		//check if the query works, if not create error message
		if( !$insert ){
			$message = 'Error! : ' . mysqli_error( $dbc ) ;
			return $message;
		}else{
			//if the users quick_cv is creates then update the Users table so the it shows the users quick_cv status as true (=1)
			$activate_cv = "UPDATE Users SET QuickCV = '1' WHERE Id = '$user_id'";
			$activate_cv_query = mysqli_query ( $dbc , $activate_cv ) ;
			//check if query works, if not create error
			if( !$activate_cv_query ){
				$message = 'Error! : ' . mysqli_error( $dbc ) ;
				return $message;
			}
		}
	}

	return $message;
	
}

/**
*
* Sets the matching users 'Unsubscribe' database value as 0 or unsubscribed
* If the email and password don't match a user, it should return an error 
*
* Jack Scott
*
*/
function unsubscribe( $dbc , $email = '' , $pass = '' ){
	$email_query = "SELECT Salt, Password FROM Users WHERE Email = '$email'" ;
	$sender_email = mysqli_query ( $dbc , $email_query ) ;
	//check that the queries successfully reach the database
	if( $sender_email ){
		//get the values that match the query request
		$quick_array = mysqli_fetch_assoc( $sender_email );
		//encode the password input by the user with the Salt
		$enc_pass = SHA1( $pass . $quick_array['Salt'] );
		//check if the encoded password input by user and one in database match
		//log the user in if they match and return and error if they do not
		if($quick_array['Password'] == $enc_pass){
			$unsub_query = "UPDATE Users SET Unsubscribed = '1' WHERE Email = '$email'";
			$unsub_action = mysqli_query ( $dbc , $unsub_query ) ;
			//check if query works, if not create error
			if( !$unsub_action ){
				$message = 'Error! : ' . mysqli_error( $dbc ) ;
				return $message;
			}
		}else{
			$message = 'The password you entered was incorrect.';
			return $message;
		}
	}else{
		$message = 'Error! : ' . mysqli_error( $dbc ) ;
		return $message;
	}
}

?>