<?php
session_start();
$TITLE = "Preferences";
require('includes/reqdocs.php');

if(empty($_SESSION['Id']))
{
    header("Location: login.php");
    exit();
}

$id = $user->get("Id");

$query = "SELECT CourseJSONObj FROM Users WHERE Id = '$id'";
if($result = $mysqli->query($query))
{
	$row = $result->fetch_assoc();
	$tempArray = json_decode($row['CourseJSONObj'], true);
	if(is_array($tempArray))
	{
		foreach($tempArray as $cid)
		{
			$query = "SELECT CourseCode, Id FROM Courses WHERE Id = '$cid'";
			if($result = $mysqli->query($query))
			{
				while($row = $result->fetch_assoc())
				{
					$optArray[] = $row;
				}
				$result->free();
			}else{
				$errors[] = "QUERY TO SERVER FAILED: " . $mysqli->error;
			}
		}
	}
}else{
	$errors[] = "QUERY TO SERVER FAILED: " . $mysqli->error;
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
			<p>Your update was successful.</p>
		<?php endif; ?>
		<p>Search courses by either <u>name</u> or <u>unit code</u>.</p>
		<p>There is a maximum number of 6 units at a time (for the semester).</p>
	</div>
</div>
<div class="text-right">
	<p>Upon selecting a course, It should appear below within a few seconds.</p>
	<input type="text" placeholder="Search for Course" id="courseSearch">
	<ul class="course-search-options"></ul>
	<ul class="course-active">
		<?php
		if(is_array($optArray))
		{
			foreach($optArray as $val){
				echo '<li><p>' . $val['CourseCode'] . '</p><div class="unitId_' . $val['Id'] . '"><p>REMOVE</p></div></li>';
			}
		}
		?>
	</ul>
</div>
<?php
include("includes/footer.php");
?>