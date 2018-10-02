<?php
class ajaxValidate {
 
        function formValidate($dbc) {
        	//get the data values from post and session for query
            $job_id_here = @$_POST['fav_job'];
            $user_id = @$_POST['user_id'];
            //create a return array
			$return = array();
			//create error message variable in the return array, default it to nothing
			$return['message'] = '';
			$return['state'] = '';
			$return['job_id'] = $job_id_here;
            if(empty($user_id) || $user_id == 0){
        		$return['message'] = 'To Favourite a Job, you must first be signed into an account.';
        	}else{
				require('../../../connect_db.php');
				//the query to check if already favourited this job
				$fav_query = "SELECT Id FROM FavJobs WHERE JobId = '$job_id_here' AND UserId = '$user_id'";
				$fav_action = mysqli_query( $dbc , $fav_query );
				//check if query is successful, if not return an error
				if(!$fav_action){
					$return['message'] = 'There is a promblem with our server : ' . mysqli_error( $dbc );
				}else{
					//if query is successful then check number of rows that match
					//if matching rows > 0 then the user has already favourited this job
					$num_check_applied = mysqli_num_rows( $fav_action );
					if($num_check_applied > 0){
						//Drop the favourite row matching this user and the job id
						$add_fav_query = "DELETE FROM FavJobs WHERE JobId = '$job_id_here' AND UserId = '$user_id'" ;
						$insert_fav = mysqli_query( $dbc , $add_fav_query );
						//check if the appliaction to AppliedJobs data table was successful
						if(!$insert_fav){
							$return['message'] = 'There is a promblem with our server : ' . mysqli_error( $dbc );
						}else{
							$return['state'] = false;
						}
					}else{
						//create new row in FavJobs
						$direction = $_POST['direction'];
						$emp_id = $_POST['emp_id'];
						$time_now = date("F j, Y");
						$add_fav_query = "INSERT INTO FavJobs ( JobId , UserId , TimeOfFav )
											VALUES ( '$job_id_here' , '$user_id' , '$time_now' )" ;
						$insert_fav = mysqli_query( $dbc , $add_fav_query );
						//check if the appliaction to AppliedJobs data table was successful
						if(!$insert_fav){
							$return['message'] = 'There is a promblem with our server : ' . mysqli_error( $dbc );
						}else{
							$return['state'] = true;
						}
					}
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