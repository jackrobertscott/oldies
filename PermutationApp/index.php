<?php 
if($_GET['word'] == 'empty')
	$errors[] = "Please enter a word.";
include('includes/header.php'); 
foreach($errors as $value)
	echo '<span>'.$value.'</span>';
?>

<form action="result.php" method="GET">
	<input type="text" placeholder="Insert word" name="word">
	<input type="submit" value="GO" class="go-button">
</form>

<?php include('includes/footer.php'); ?>