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

<form action="update.php" method="POST">
	<input type="text" placeholder="Name" name="name" <?php if(!empty($_POST['name'])){echo 'value="'.$_POST['name'].'"';}else{echo 'value="'.$user->get("Name").'"';}?>>
	<input type="submit" value="submit">
</form>

<?php
include("includes/footer.php");
?>