<?php
session_start();
$TITLE = "About";
require('../theme/config.php');
require(ASSETS.'open.php');

//code

include("../theme/header.php");
?>

<?php
foreach ($ABOUT_PARAS as $para) {
	echo '<p class="plr-14 pt-14">'.$para.'</p>';
}
?>
<span class="pb-14"></span>

<?php
include("../theme/footer.php");
?>