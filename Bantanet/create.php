<?php
session_start();
$TITLE = "Register Course";
require('includes/reqdocs.php');

if(empty($_SESSION['Id']))
{
    header("Location: login.php");
    exit();
}

if($_SERVER[ 'REQUEST_METHOD' ] == 'POST'){
	$pattern = "/[A-Z]{4}+[0-9]{4}/";
	if(empty($_POST['courseCode']))
	{
		$errors[] = 'The Course Unit Code input area is empty.';
	}elseif(!preg_match($pattern, $_POST['courseCode'])){
		$errors[] = 'The Course Unit is not in a correct format. <br>eg. ABCD1234';
	}else{
		$courseCode = $mysqli->real_escape_string(trim($_POST['courseCode']));
		$courseCode = strip_tags($courseCode);
	}
	if(empty($_POST['name']))
	{
		$errors[] = 'The Course Name input area is empty.';
	}else{
		$name = $mysqli->real_escape_string(trim($_POST['name']));
		$name = strip_tags($name);
	}
	if(empty($errors))
	{
		$course = new Course();
		if(!$course->dbInsertCourse($courseCode, $name, $user->get('University'), $user->get('Id')))
			$errors[] = $course->getErrors();
	}
}

include("includes/header.php");
?>
<div class="text-title">
	<div class="title">
		<h1><?php echo $TITLE; ?></h1>
	</div>
</div>
<div class="text-left">
	<div class="desc">
		<?php if(!empty($errors)): ?>
			<?php include('includes/error-notice.php'); ?>
		<?php elseif($_SERVER[ 'REQUEST_METHOD' ] == 'POST'): ?>
			<h3>Saved</h3>
			<p>Your course creation was successful.</p>
		<?php else: ?>
			<p>Submit Information into the following boxes to add a university course.</p>
			<p>Please try to be as accurate as possible as this will be used to allow other users to find you.</p>
		<?php endif; ?>
	</div>
</div>
<div class="text-right">
	<form action="create.php" method="POST">
		<input type="text" placeholder="Course Unit Code (8 characters)" maxlength="8" name="courseCode" <?php if(!empty($_POST['courseCode'])){echo 'value="' . $_POST['courseCode'] . '"';}?>>
		<input type="text" placeholder="Course Name (formal)" name="name" <?php if(!empty($_POST['name'])){echo 'value="' . $_POST['name'] . '"';}?>>
		<input type="submit" class="text-submit" value="submit">
	</form>
</div>
<?php
include("includes/footer.php");
?>