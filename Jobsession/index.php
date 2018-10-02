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

if ( ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') && (!$_SESSION['log']) ){
  
  if( empty( $_POST['username'] ) ){
    $errors[] = 'Your username input box is empty';
  } else {
    $username = mysqli_real_escape_string( $dbc , trim($_POST['username']) );
    $username = strip_tags($username);
  }
  
  if( empty( $_POST['password'] ) ){
    $errors[] = 'Your password input box is empty';
  } else {
    $password = mysqli_real_escape_string( $dbc , trim($_POST['password']) );
    $password = strip_tags($password);
  }

  if( empty($errors) ){
    $message = checkUser( $_POST['keepin'] , $username , $password , $dbc );
    if(!empty($message)){
      $errors[] = $message;
    }
  }
  
}

include('includes/header.php');
?>
<?php include('includes/log_in_bar.php'); ?>
<div class="index_main">
  <div class="index_back_img"></div>
  <div class="index_back_color"></div>
  <div class="index_content">
    <form method="GET" id="ref_query_sub" action="search.php">
      <div class="mps-wrap clearfix">
        <div class="mps-title clearfix">
          <div class="mps-title-search clearfix">
            <p class="text">Job Search&nbsp;</p>
          </div>
          <p onClick="window.location='job_submit_ini.php';" class="text text-2">Post a Free Job <span class="icon-export"></span></p>
        </div>
        <input class="mps-keywords" name="search_query" placeholder="Keywords - seperate words with a space" type="text">
        <div class="mps-select mps-select-1 clearfix">
          <div class="mps-opt-title clearfix">
            <p class="text">Category</p>
          </div>
          <ul class="mps-opt-hold ref_overflow over_cat" style="display:block;border:none;">
            <li><p>Accounting</p></li>
            <li><p>Administration</p></li>
            <li><p>Advertising, Arts and Media</p></li>
            <li><p>Banking and Financial Services</p></li>
            <li><p>Call Centre and Customer Service</p></li>
            <li><p>CEO and General Management</p></li>
            <li><p>Community Services and Development</p></li>
            <li><p>Construction</p></li>
            <li><p>Consulting and Strategy</p></li>
            <li><p>Design and Architecture</p></li>
            <li><p>Education and Training</p></li>
            <li><p>Engineering</p></li>
            <li><p>Farming, Animals and Conservation</p></li>
            <li><p>Government and Defence</p></li>
            <li><p>Healthcare and Medical</p></li>
            <li><p>Hospitality and Tourism</p></li>
            <li><p>Human Resources and Recruitment</p></li>
            <li><p>Information and Communication Technology</p></li>
            <li><p>Insurance and Superannuation</p></li>
            <li><p>Legal</p></li>
            <li><p>Manufacturing, Transportation and Logistics</p></li>
            <li><p>Marketing and Communications</p></li>
            <li><p>Mining, Resources and Energy</p></li>
            <li><p>Real Estate and Property</p></li>
            <li><p>Retail and Consumer Products</p></li>
            <li><p>Sales</p></li>
            <li><p>Science and Technology</p></li>
            <li><p>Self Employment</p></li>
            <li><p>Sport and Recreation</p></li>
            <li><p>Trades and Services</p></li>
          </ul>
        </div>
        <div class="mps-select mps-select-2 clearfix">
          <div class="mps-opt-title clearfix">
            <p class="text">Location</p>
          </div>
          <ul class="mps-opt-hold ref_overflow over_loc" style="display:block;border:none;">
            <li><p>Australian Capital Territory</p></li>
            <li><p>Nothern Territory</p></li>
            <li><p>New South Wales</p></li>
            <li><p>Queensland</p></li>
            <li><p>South Australia</p></li>
            <li><p>Tasmania</p></li>
            <li><p>Western Australia</p></li>
            <li><p>Victoria</p></li>
          </ul>
        </div>
        <div class="mps-select mps-select-3 clearfix">
          <div class="mps-opt-title clearfix">
            <p class="text">Type</p>
          </div>
          <ul class="mps-opt-hold ref_overflow over_sal" style="display:block;border:none;">
            <li><p>To Be Discussed</p></li>
            <li><p>Full Time</p></li>
            <li><p>Part Time</p></li>
            <li><p>Casual</p></li>
            <li><p>Contract</p></li>
            <li><p>Temporary</p></li>
          </ul>
        </div>
        <input type="hidden" name="gen_loc_form" value="<?php if(!empty($_GET['gen_loc_form'])){ echo $_GET['gen_loc_form']; }else{ echo 'null'; } ?>">
        <input type="hidden" name="gen_sal_form" value="<?php if(!empty($_GET['gen_sal_form'])){ echo $_GET['gen_sal_form']; }else{ echo 'null'; } ?>">
        <input type="hidden" name="gen_cat_form" value="<?php if(!empty($_GET['gen_cat_form'])){ echo $_GET['gen_cat_form']; }else{ echo 'null'; } ?>">
        <input type="submit" value="GO" class="mps-submit">
      </div>
    </form>
  </div>
</div>
<?php 
$no_search = true;
include("includes/footer.php");
?>
