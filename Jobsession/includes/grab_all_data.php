<?php
$user_array = array();
//store all employer data 
$emp_array = array();

$user_query = "SELECT * FROM Users WHERE Id = '$id'";
$user_data = mysqli_query( $dbc , $user_query );
			
if( $user_data ){
	$user_assoc = mysqli_fetch_assoc( $user_data );
	if (is_array($user_assoc))
	{
		foreach ($user_assoc as $key => $value) {
			$user_array[$key] = $value;
		}
	}
} else {
	$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
}

$emp_query = "SELECT * FROM Employers WHERE UserId = '$id'";
$emp_data = mysqli_query( $dbc , $emp_query );

if( $emp_data ){
	$emp_assoc = mysqli_fetch_assoc( $emp_data );
	if (is_array($emp_assoc))
	{
		foreach ($emp_assoc as $key => $value) {
			$emp_array[$key] = $value;
		}
	}
} else {
	$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
}

if(!empty($_COOKIE['id']) && !$_SESSION['log']){
	log_in_with_cookie( $_COOKIE['id'] , $dbc );
}
?>