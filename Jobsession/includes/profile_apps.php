<?php
$job_id = $job_data_inputs['Id'];
$forms_query = "SELECT UserId FROM AppliedJobs WHERE JobId = '$job_id'";
$forms_action = mysqli_query( $dbc , $forms_query );
if(!$forms_action){
	$errors = array();
	$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
	echo "<div class='right_text'>";
		echo "<p>The following errors were found : </p>"; 
		foreach( $errors as $msg ){ echo "<p>$msg</p>" ; }
	echo "</div><!-- right_text -->";
}else{
	$number_of_results = mysqli_num_rows($forms_action);
	if($number_of_results > 0){
		echo "<div class='right_text'><p>The following people have applied for this Job.</p></div><!-- right_text -->";
	}
	while($forms_assoc = mysqli_fetch_assoc( $forms_action )){
		$forms_user = $forms_assoc['UserId'];
		$det_query = "SELECT FirstName, LastName FROM QuickCV WHERE UserId = '$forms_user'";
		$det_action = mysqli_query( $dbc , $det_query );
		if(!$det_action){
			$errors = array();
			$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc ) ;
			echo "<div class='right_text'>";
				echo "<p>The following errors were found : </p>"; 
				foreach( $errors as $msg ){ echo "<p>$msg</p>" ; }
			echo "</div><!-- right_text -->";
		}else{
			$det_assoc = mysqli_fetch_assoc( $det_action );
			echo '<form class="app_form">
					<div class="app_thumb">
						<p>' . $det_assoc['FirstName'] . " " . $det_assoc['LastName'] . '<span class="icon-vcard" style="float: right;font-size: 20px;"></span></p>
						<input type="hidden" name="app_id" value="' . $forms_user . '">
						<input class="sub" type="submit">
					</div>
				</form>';
		}
	}
}
?>
