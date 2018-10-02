<?php
session_start();
$TITLE = "Update";
require('includes/reqdocs.php');

if(empty($_SESSION['UserId']))
{
    header("Location: login.php");
    exit();
}

if(!empty($_GET['post'])){
	Base::sanGET();
	$thisId = $_GET['post'];
}else{
	header("Location: index.php");
    exit();
}

if(!empty($_POST['comment'])){
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

include("includes/header.php");
?>

<div class="post corner shadow">
	<?php
	$query = "SELECT Users.Name, Posts.Message, Posts.Time, Posts.Id, Posts.Video
			  FROM Users
			  INNER JOIN Posts
			  ON Users.Id = Posts.UserId
			  WHERE Posts.Id = $thisId";
	$result = $mysqli->query($query);
	$row = $result->fetch_assoc();
	?>
  <!--
  <div class="rating">
    <div class="green" style="width:50%;"></div>
  </div>
  -->
  <p><?php echo $row['Message']; ?></p>
  <?php if(!empty($row['Video'])): ?>
  	<div class="videoWrapper">
  	    <iframe width="560" height="349" src="<?php echo $row['Video']; ?>" frameborder="0" allowfullscreen></iframe>
	</div>
  <?php endif; ?>
	  <div class="comments">
	  	<?php 
		$query = "SELECT Users.Name, Comments.Message, Comments.Time
				  FROM Users
				  INNER JOIN Comments
				  ON Users.Id = Comments.UserId
				  WHERE Comments.PostId = $thisId
				  ORDER BY Comments.Id desc";
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

<div class="text corner shadow">
	<div class="inp-wrap">
		<p><a href="index.php"><< back</a></p>
	</div>
</div>

<?php
include("includes/footer.php");
?>