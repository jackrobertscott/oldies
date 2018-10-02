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

$page_title = "Search";
include ('includes/header.php');
?>
		
<?php include('includes/log_in_bar.php'); ?>

<div class="center_it">
	<div class="main_content" >
	
	<?php include ('includes/sidebar.php'); ?>
	
		<div class="main_right">
		
			<?php
			if($_SERVER[ 'REQUEST_METHOD' ] == 'GET'){
				if( $_GET['gen_loc_form'] != 'null' ){
					$gen_loc_ser = $_GET['gen_loc_form'];
				}
				if( $_GET['gen_sal_form'] != 'null' ){
					$gen_sal_ser = $_GET['gen_sal_form'];
				}
				if( $_GET['gen_cat_form'] != 'null' ){
					$gen_cat_ser = $_GET['gen_cat_form'];
				}
				$search_query = $_GET['search_query'];
				$universal_query = "
									SELECT * 
									FROM JobSubmit 
									WHERE Active = '1' 
									AND (`Category` LIKE '%".$gen_cat_ser."%') 
									AND (`PriorityCat` = '1') 
									ORDER BY RAND()
									"; 
				include('includes/the_priority_query.php');
				unset($universal_query);
				$search_words = explode(" ", $search_query);
				$universal_query = "
									SELECT * 
									FROM JobSubmit 
									WHERE Active = '1'
									AND (`Category` LIKE '%".$gen_cat_ser."%') 
									AND (`Location` LIKE '%".$gen_loc_ser."%') 
									AND (`TypeTime` LIKE '%".$gen_sal_ser."%')
									";
				foreach ($search_words as $sw){
					$universal_query .= " AND ( 
										(`JobName` LIKE '%".$sw."%') 
										OR (`Description` LIKE '%".$sw."%') 
										OR (`Category` LIKE '%".$sw."%') 
										OR (`Location` LIKE '%".$sw."%') 
										OR (`RequirementArray` LIKE '%".$sw."%') 
										OR (`Category` LIKE '%".$sw."%') 
										OR (`Location` LIKE '%".$sw."%') 
										OR (`TypeTime` LIKE '%".$sw."%')
										OR (`Income` LIKE '%".$sw."%')
										)";
				}
				$universal_query .= " ORDER BY Id DESC";
				$search_page = true;
				include('includes/the_job_query.php');
			}else{
				echo '<div class="right_art">
						<div class="text">
							<p>You are not searching for anything.</p>
						</div><!-- text -->
					</div><!-- right_art -->';
			}
			?>
						
		</div><!-- main_right -->
	</div><!-- main_content -->
</div><!-- center_it -->

<?php
include ('includes/footer.php');
?>