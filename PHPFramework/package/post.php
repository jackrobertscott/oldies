<?php
session_start();
require('includes/reqdocs.php');

if(empty($_SESSION['UserId']))
{
    header("Location: login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	Base::sanPOST();
	$inpArray = array("message");
	foreach($inpArray as $value){
		if(empty($_POST[$value]))
			$errors[] = 'The '.$value.' input is empty.';
	}
	if(empty($errors) && !empty($_FILES['image']['name']))
	{
		$img = new Image();
		$nw = 400;
		$nh = 400;
		if(!$img->upload("image", "tmp", $nw, $nh))
		{
			$errors = $img->getErrors();
		}else{
			if(!$img->create($user->getId(), true, $img->getLocation()))
				$errors = $img->getErrors();
		}
	}
	if(empty($errors))
	{
		$extras = array();
		if(isset($img))
			$extras["ImageId"] = $img->getImageId();
		$post = new Activity(TABLE_POSTS);
		if(!$post->create($user->getId(), $_POST['message'], $extras))
			$errors = $post->getErrors();
	}
}

include("includes/header.php");
?>
<table>
<?php 
$query = "SELECT Users.Name, Posts.Message, Posts.Time, Images.Location
		  FROM Users
		  INNER JOIN Posts
		  ON Users.Id = Posts.UserId
		  LEFT JOIN Images 
		  ON Images.Id = Posts.ImageId";
$result = $mysqli->query($query);
while($row = $result->fetch_array()):
?>
<tr>
	<td><?php echo $row['Name']; ?></td>
	<td><?php echo $row['Message']; ?></td>
	<td><?php echo $row['Time']; ?></td>
	<td><img src="<?php echo $row['Location']; ?>" height="100"/></td>
</tr>
<?php endwhile; ?>
</table><br><br>

<form action="post.php" enctype='multipart/form-data' method="POST">
	<input type="file" name="image">
	<textarea name="message" placeholder="Message"><?php if(!empty($_POST['message'])) echo $_POST['message']; ?></textarea>
	<input type="submit" value="submit">
</form>

<?php
include("includes/footer.php");
?>