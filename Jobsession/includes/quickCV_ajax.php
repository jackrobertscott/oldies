<?php
class ajaxValidate {
 
        function formValidate($dbc) {
        	//get the data values from post and session for query
            $user_id = @$_POST['app_id'];
            //create a return array
			$return = array();
			//create error message variable in the return array, default it to nothing
			$return['message'] = '';
			$return['fname'] = '';
			$return['lname'] = '';
			$return['about'] = '';
			$return['edu'] = '';
			$return['exp'] = '';
			$return['email'] = '';
			$return['res'] = '';
            if($user_id == 0){
        		$return['message'] = 'There is an error with our servers, we apologise for the inconvenience';
        	}else{
				require('../../../connect_db.php');
				//the query to check if already favourited this job
				$fav_query = "SELECT * FROM QuickCV WHERE UserId = '$user_id'";
				$fav_action = mysqli_query( $dbc , $fav_query );
				//check if query is successful, if not return an error
				if(!$fav_action){
					$return['message'] = 'There is a promblem with our server : ' . mysqli_error( $dbc );
				}else{
					$app_dets = mysqli_fetch_assoc( $fav_action );
					$return['fname'] = nl2br($app_dets['FirstName']);
					$return['lname'] = nl2br($app_dets['LastName']);
					$return['about'] = nl2br($app_dets['About']);
					$return['edu'] = nl2br($app_dets['Education']);
					$return['exp'] = nl2br($app_dets['Experience']);
					$return['email'] = nl2br($app_dets['Email']);
					$return['res'] = nl2br($app_dets['Resume']);
				}
				mysqli_close( $dbc );
			}
			//return the $return array to be used in the the_job_query.php
			return json_encode($return);
        }
 
}
 
$ajaxValidate = new ajaxValidate;
echo $ajaxValidate->formValidate($dbc);
?>