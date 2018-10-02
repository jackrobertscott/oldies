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

include("includes/header.php");
?>

<div class="text corner shadow">
	<form action="update.php" method="POST">
		<div class="inp-wrap">
			<p>Full Name</p>
			<input type="text" placeholder="Name" name="name" <?php if(!empty($_POST['name'])){echo 'value="'.$_POST['name'].'"';}else{echo 'value="'.$user->get("Name").'"';}?>>
		</div>
		<div class="inp-wrap">
			<input type="submit" value="submit">
		</div>
	</form>
</div>

<?php
include("includes/footer.php");
?>