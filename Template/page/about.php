<?php
session_start();
$TITLE = "About";
require('../theme/config.php');
require(ASSETS.'open.php');

//code

include("../theme/header.php");
?>

<div class="content m-a oh pf-14 mt-14">
	<?php
	foreach ($ABOUT_PARAS as $para) {
		echo '<p>'.$para.'</p>';
	}
	?>
</div>

<?php
include("../theme/footer.php");
?>