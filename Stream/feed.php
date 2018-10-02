<?php
session_start();
$TITLE = "New Stream";
require('../theme/config.php');
require(ASSETS.'open.php');

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	Base::sanPOST();
	$inpArray = array("name");
	foreach($inpArray as $value){
		if(empty($_POST[$value]))
			$errors[] = 'The '.$value.' input is empty.';
	}
	if(empty($errors))
	{
		
	}
}

include("../theme/header.php");
?>

<?php if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors)): ?>
	<p class="pf-14">Your new Stream has been created.</p>
<?php else: ?>
	<form name="form" class="css-form" action="feed.php" method="POST" novalidate>
		<label class="w-100-28 oh pf-14 fs-13">
			New Stream Name
			<input class="fr pf-7" type="text" placeholder="Name" ng-model="name" name="name" <?php if(!empty($_POST['name'])){echo 'ng-init="name=\''.$_POST['name'].'\'"';}?> required/>
		</label>
		<span class="w-100 fr ta-r mb-7 fs-10" ng-show="form.email.$invalid">Please insert a name for your new Stream.</span>
		<button class="fr pf-7 mlr-14 mb-14" ng-disabled="form.$invalid">Create</button>
	</form>
<?php endif; ?>

<?php
include("../theme/footer.php");
?>