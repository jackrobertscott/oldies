<?php
session_start();
$TITLE = "Update";
require('includes/reqdocs.php');

if(empty($_SESSION['UserId']))
{
    header("Location: login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	Base::sanPOST();
	$inpArray = array("name");
	foreach($inpArray as $value){
		if(empty($_POST[$value]))
			$errors[] = 'The '.$value.' input is empty.';
	}
	if(empty($errors))
	{
		$args = array(
		"Name" => $_POST['name']
		);
		$user->dbUpdate($args);
		$errors = $user->getErrors();
	}
}

include("theme/head.php");
?>

<?php if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors)): ?>
<div class="content">
	<p>Account Updated.</p>
</div>
<?php endif; ?>
<div class="content">
	<form name="form" class="css-form" action="update.php" method="POST" novalidate>
		<label>Full Name
			<input type="text" placeholder="Name" ng-model="name" name="name" <?php if(!empty($_POST['name'])){echo 'ng-init="name=\''.$_POST['name'].'\'"';}else{echo 'ng-init="name=\''.$user->get("Name").'\'"';}?>/>
		</label><br/>
		<button ng-disabled="form.$invalid">Submit</button>
	</form>
</div>

<?php
include("theme/foot.php");
?>