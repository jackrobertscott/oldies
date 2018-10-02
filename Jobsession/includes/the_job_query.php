<?php
$universal_action = mysqli_query( $dbc , $universal_query );
$rows_per_page = 10 ;
if( !$universal_action ){
	$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc );
	$dont_show_data = true;
}else{
	$number_of_results = mysqli_num_rows($universal_action);
	if($number_of_results != 0){
		$pages = ceil($number_of_results / $rows_per_page);
	}
}
mysqli_free_result($universal_action);
$screen = $_GET['screen'];
if(!isset($screen)){ 
	$screen = 0 ; 
}
$start = $screen * $rows_per_page;
$universal_query .= " LIMIT $start, $rows_per_page";
$universal_action = mysqli_query( $dbc , $universal_query );
?>

<?php if( $number_of_results == 0 || empty($pages) ) : ?>
	<div class="right_art">
		<div class="text">
			<p>No results matching that search.</p>
		</div><!-- text -->
	</div><!-- right_art -->
<?php elseif ( $search_page == true ) : ?>
	<div class="right_art">
		<div class="text">
			<p><?php echo "$number_of_results" ; ?> Job<?php if($number_of_results > 1){ echo "s" ; }?> found for that search.</p>
		</div><!-- text -->
	</div><!-- right_art -->
<?php endif; ?>

<? while( ($uni_data = mysqli_fetch_assoc( $universal_action )) && ($number_of_results > 0) ) : ?>
	
	<?php
	$posts_on_page ++ ;
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
	$job_id = $uni_data['Id'];
	$fav_query = "SELECT Id FROM FavJobs WHERE JobId = '$job_id' AND UserId = '$id'";
	$fav_action = mysqli_query( $dbc , $fav_query );
	$fav_status = false;
	//check if query is successful, if not return an error
	if(!$fav_action){
		$errors[] = 'There is a promblem with our server : ' . mysqli_error( $dbc );
	}else{
		//if query is successful then check number of rows that match
		//if matching rows > 0 then the user has already favourited this job
		$num_check_fav = mysqli_num_rows( $fav_action );
		if($num_check_fav > 0){
			$fav_status = true;
		}
	}
	$this_is_now = time();
	$your_date = $uni_data['TimeDifference'];
	$datediff = ($this_is_now - $your_date);
	$days_since_post = floor($datediff/(60*60*24));
	if( $days_since_post > 30 ){ //Number of days allowed to be active here!
		$this_post_id = $uni_data['Id'];
		$deact_job_query = "UPDATE JobSubmit SET Active = '0' WHERE Id = '$this_post_id'";
		if(mysqli_query($dbc , $deact_job_query)){
			$post_overdate = true ;
		}else{
			$errors[] = 'Error : ' . $this_post_id . " " . mysqli_error( $dbc );
		}
	}
	?>
	
	<?php if( !empty($errors) ) : ?>
		<div class="right_art">
			<div class="text">
				<?php foreach( $errors as $err ){ echo "<p>$err</p>" ; } ?>
			</div><!-- text -->
		</div><!-- right_art -->
	<?php endif; ?>

	<?php if(!$post_overdate) : ?>
	
		<?php if( (!empty($uni_data['DisplayImage'])) && ($dont_show_data != true) ) : ?>
			
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
					<form class="thepreviewjob favourite">
						<input type="hidden" name="fav_job" value="<?php echo $uni_data['Id'] ; ?>">
						<input type="hidden" name="user_id" value="<?php if(!empty($id)){ echo $id ; }else{ echo "0"; } ?>">
						<input type="submit" class="apply_for fav_ajax<?php if($fav_status){ echo " fav_true"; } ?>" title="Favourite this Job" value="">
					</form>
					<form action="full_job_info.php" method="GET" class="thepreviewjob" id="form<?php echo $uni_data['Id'] ; ?>">
						<input type="hidden" name="return_job" value="<?php echo $uni_data['Id'] ; ?>">
						<div class="go_to_exp" onClick="document.forms['form<?php echo $uni_data['Id'] ; ?>'].submit();">
							<h1 class="icon-export" title="Check out this Job"></h1>
						</div>
					</form>
					<div class="text_title">
						<div class="main">
							<h1><?php echo $uni_data['JobName'] ; ?></h1>
						</div>
						<h2><?php echo $uni_data['TimeOfSubmit'] ; ?><?php echo $days_since_post ; ?></h2>
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
					<form class="thepreviewjob favourite">
						<input type="hidden" name="fav_job" value="<?php echo $uni_data['Id'] ; ?>">
						<input type="hidden" name="user_id" value="<?php if(!empty($id)){ echo $id ; }else{ echo "0"; } ?>">
						<input type="submit" class="apply_for fav_ajax<?php if($fav_status){ echo " fav_true"; } ?>" title="Favourite this Job" value="">
					</form>
					<form action="full_job_info.php" method="GET" class="thepreviewjob" id="form<?php echo $uni_data['Id'] ; ?>">
						<input type="hidden" name="return_job" value="<?php echo $uni_data['Id'] ; ?>">
						<div class="go_to_exp" onClick="document.forms['form<?php echo $uni_data['Id'] ; ?>'].submit();">
							<h1 class="icon-export" title="Check out this Job"></h1>
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

	<?php endif ; ?>

	<?php $errors = array(); ?>

<?php endwhile ; ?>

<?php if( !empty($pages) && $number_of_results > 0 ) : ?>
	<div class="right_art">
		<div class="text">
			<p style="text-align: center;">
			<?php
			$url_len = strlen( $_SESSION['url1'] ) ;
			$url_scr_pos = strpos( $_SESSION['url1'] , '&screen=' ) ;
			$url_scr_pos_q = strpos( $_SESSION['url1'] , '?screen=' ) ;
			if( $url_scr_pos !== false ){ 
				$_SESSION['url1'] = substr( $_SESSION['url1'] , 0 , ($url_len - ($url_len - $url_scr_pos)) ) ; 
			}elseif( $url_scr_pos_q !== false ){
				$_SESSION['url1'] = substr( $_SESSION['url1'] , 0 , ($url_len - ($url_len - $url_scr_pos_q)) ) ;
			}
			$have_a_q = strpos( $_SESSION['url1'] , '?' ) ; //This must be after string removal (lines above)
			if ($screen > 0) {
				if( $have_a_q == false ){
			  		$url = $_SESSION['url1'] . "?screen=" . ($screen - 1);
			  		echo "<a href=\"$url\">Previous</a> | ";
			  	}else{
			  		$url = $_SESSION['url1'] . "&screen=" . ($screen - 1);
			  		echo "<a href=\"$url\">Previous</a> | ";
			  	}
			}
			for ($i = 1; $i < ($pages + 1); $i++) {
				if( $have_a_q == false ){
					$url = $_SESSION['url1'] . "?screen=" . ($i - 1);
				}else{
					$url = $_SESSION['url1'] . "&screen=" . ($i - 1);
				}
				if( ($i >= $screen - 1) && ($i <= $screen + 3) ){
					if( $screen == $pages - 1 && $pages == $i ){
						if( $i == $screen + 1 ){
							echo "<a href=\"$url\"><u>$i</u></a> ";
						}else{
							echo "<a href=\"$url\">$i</a> ";
						}
					}else{
						if( $i == $screen + 1 ){
							echo "<a href=\"$url\"><u>$i</u></a> | ";
						}else{
							echo "<a href=\"$url\">$i</a> | ";
						}
					}
				}
			}
			if ($screen < ($pages - 1) ){
				if( $have_a_q == false ){
					$url = $_SESSION['url1'] . "?screen=" . ($screen + 1);
			  		echo " <a style=\"text-align:center;\" href=\"$url\">Next</a>";
				}else{
					$url = $_SESSION['url1'] . "&screen=" . ($screen + 1);
			  		echo "| <a style=\"text-align:center;\" href=\"$url\">Next</a>";
				}
			}
			//This Script could use some clean up
			//Need to Add **$not_just_start = true ;** to all search query pages such as search.php and refine_query_search.php
			?>
			</p>
		</div><!-- text -->
	</div><!-- right_art -->
<?php endif ; ?>