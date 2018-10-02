<?php
session_start();
require('includes/reqdocs.php');

if(!$user->get("Verified") && !empty($_SESSION['UserId']))
{
	$errors[] = "You must verify your account to be able to post or comment.";
}elseif($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['UserId'])){
	Base::sanPOST();
	if(!empty($_POST['message'])){
		$inpArray = array("message", "category");
		foreach($inpArray as $value){
			if(empty($_POST[$value]))
				$errors[] = 'The '.$value.' input is empty.';
		}
		if(empty($errors))
		{
			$extras = array(
			"Category" => $_POST['category'],
			"Video" => $_POST['video']
			);
			$post = new Activity(TABLE_POSTS);
			if(!$post->create($user->getId(), $_POST['message'], $extras))
				$errors = $post->getErrors();
		}
	}elseif(!empty($_POST['comment'])){
		$inpArray = array("comment", "post");
		foreach($inpArray as $value){
			if(empty($_POST[$value]))
				$errors[] = 'The '.$value.' input is empty.';
		}
		if(empty($errors))
		{
			$extras = array("PostId" => $_POST['post']);
			$post = new Activity(TABLE_COMMENTS);
			if(!$post->create($user->getId(), $_POST['comment'], $extras))
				$errors = $post->getErrors();
		}
	}
}

$query = "SELECT Posts.Message, Posts.Time, Posts.Id, Posts.Category, Posts.Video
		  FROM Posts";
if(!empty($_GET['category']))
{
	Base::sanGET();
	$cat = $_GET['category'];
	$query .= " WHERE Posts.Category = '$cat'";
}	
$query .= " ORDER BY Posts.Id desc";
include("includes/pageIndexHead.php");

include("includes/header.php");
?>

<?php if(!empty($_SESSION['UserId'])): ?>
<div class="text corner shadow">
	<form action="<?php echo $_SERVER[REQUEST_URI]; ?>" method="POST">
		<div class="inp-wrap">
			<p style="float: left;">Post</p>
			<textarea name="message" placeholder="Message"></textarea>
		</div>
		<div class="inp-wrap">
			<select name="category" value="category">
				<option>Category</option>
				<option value='Clubs'>Clubs</option>
				<option value='Gym'>Gym</option>
				<option value='Uni'>Uni</option>
				<option value='Work'>Work</option>
			</select>
		</div>
		<div class="inp-wrap">
			<p>Youtube Video Url [optional]</p>
			<input type="text" placeholder="Video Url" name="video">
		</div>
		<div class="inp-wrap">
			<input type="submit" value="submit">
		</div>
	</form>
</div>
<?php endif; ?>

<?php 
$result = $mysqli->query($query);
$checkNull = $mysqli->affected_rows;
while($row = $result->fetch_array()):
	$thisId = $row['Id'];
?>
<div class="post corner shadow">
  <!--
  <div class="rating">
    <div class="green" style="width:50%;"></div>
  </div>
  -->
  <p><?php echo $row['Message']; ?></p>
  <?php if(!empty($row['Video'])): ?>
  	<div class="videoWrapper">
  		<?php echo preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
  		"<iframe width=\"420\" height=\"315\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe>",
  		$row['Video']); ?>
	</div>
  <?php endif; ?>
	  <div class="comments">
	  	<?php 
		$query = "SELECT Users.Name, Comments.Message, Comments.Time
				  FROM Users
				  INNER JOIN Comments
				  ON Users.Id = Comments.UserId
				  WHERE Comments.PostId = '$thisId'
				  ORDER BY Comments.Id desc
				  LIMIT 3";
		$res = $mysqli->query($query);
		while($com = $res->fetch_array()):
		?>
		  	<div>
		  		<p><?php echo $com['Message']; ?></p>
		  		<p><span><?php echo $com['Time']; ?> by <?php echo $com['Name']; ?></span></p>
		  	</div>
	  	<?php endwhile; ?>
  	  <?php if(!empty($_SESSION['UserId'])): ?>
  		<form action="<?php echo $_SERVER[REQUEST_URI]; ?>" method="POST">
			<input type="text" name="comment" placeholder="Comment"/>
			<input type="hidden" name="post" value="<?php echo $thisId; ?>">
		</form>
  	  <?php endif; ?>
  	</div>
  <div class="action-space">
  	<p><?php echo $row['Category']; ?></p>
    <span><p onclick="window.location='post.php?post=<?php echo $thisId; ?>'">more</p></span>
  </div>
</div>
<?php endwhile; ?>

<div class="text corner shadow">
	<?php 
	if($checkNull < 1)
		echo "No Results";
	?>
	<?php include("includes/pageIndexFoot.php"); ?>
</div>
<?php
include("includes/footer.php");
?>